<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class AirtelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/airtel/{reference}", name="app_airtel")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $client = new Client();
        // Define array of request body.
        $request_body = [
            "client_id" => "2601f408-027d-4dc5-a538-f995bf4ee17d",
            "client_secret" => "a353ed73-c3cf-4c97-8135-13f4a1976cc1",
            "grant_type" => "client_credentials"
        ];
        try {
            $response = $client->request('POST', 'https://openapiuat.airtel.africa/auth/oauth2/token', [
                'headers' => $headers,
                'json' => $request_body,
            ]);

            $accessToken = json_decode($response->getBody()->getContents(), true)['access_token'];

            $paymentHeaders = [
                'Content-Type' => 'application/json',
                'X-Country' => 'MG',
                'X-Currency' => 'MGA',
                'Authorization' => 'Bearer ' . $accessToken,
            ];

            // Récupérez les informations
            $products = []; // Tableau pour stocker les produits
            $totalAmount = 0;

            $order = $entityManager->getRepository(Commande::class)->findOneByReference($reference);

            if (!$order) {
                return new JsonResponse(['id' => 'order']);
            }

            // Exemple de boucle pour récupérer les informations de chaque produit

            foreach ($order->getDetailCommandes()->getValues() as $product) {
                // Ajoutez les informations dans le tableau des produits
                $products[] = [
                    'price_data' => [
                        'currency' => 'MGA',
                        'unit_amount' => $product->getTotal(),
                        'product_data' => [
                            'name' => $product->getProduit(),
                        ],
                    ],
                    'quantity' => $product->getQuantite(),
                    $totalAmount += $product->getPrix()
                ]; 
            }
            $totalAmount = $order->getTransporteurPrix() + $totalAmount;
            // Informations du colis
            $reference = "Testing transaction";
            $country = "MG";
            $currency = "MGA";
            $msisdn = 333012753; // Remplacez par le numéro de téléphone approprié
            $transactionId = uniqid(); // Générez un identifiant unique pour la transaction
            $paymentRequestBody = [
                "reference" => $reference,
                "subscriber" => [
                    "country" => $country,
                    "currency" => $currency,
                    "msisdn" => $msisdn,
                ],
                "transaction" => [
                    "amount" => $totalAmount,
                    "country" => $country,
                    "currency" => $currency,
                    "id" => $transactionId,
                    "products" => $products, // Ajoutez le tableau des produits ici
                ],
            ];
            $paymentResponse = $client->request('POST', 'https://openapiuat.airtel.africa/merchant/v1/payments/', [
                'headers' => $paymentHeaders,
                'json' => $paymentRequestBody,
            ]);

            $responseContent = $paymentResponse->getBody()->getContents();
            $responseData = json_decode($responseContent, true);

            if ($responseData['status']['success'] === false) {
                $errorCode = $responseData['status']['code'];
                $errorMessage = $responseData['status']['message'];
                // Traitez l'erreur en conséquence
                // Affichez le message d'erreur à l'utilisateur ou effectuez d'autres actions
            } else {
                // La transaction a réussi
                $transactionId = $responseData['data']['transaction']['id'];
                $status = $responseData['data']['transaction']['status'];
                // Traitez la transaction réussie en conséquence
                // Par exemple, mettez à jour l'état de la commande dans la base de données, envoyez une confirmation par e-mail, etc.
                if (!$order || $order->getUser() != $this->getUser()){
                    return $this->redirectToRoute('home');
                }
        
                if (!$order->isPaye()){
                    // Vider la session "cart"
                    $cart->remove();
        
                    // Modifier le statut isPaid de notre commande en mettant 1
                    $order->setPaye(1);
                    $this->entityManager->flush();
                    return $this->redirectToRoute('airtel_success');
                }
            }

        } catch (BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }
    }
     /**
     * @Route("/commande/airtel/success", name="airtel_success")
     */
    public function airtelSuccess(): Response
    {
        // Code pour gérer la page de succès après le paiement
        // Par exemple, afficher un message de succès ou rediriger vers une autre page

        return $this->render('airtel_success/index.html.twig');
    }    
}