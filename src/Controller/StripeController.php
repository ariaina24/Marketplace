<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Commande::class)->findOneByReference($reference);
        
        if (!$order){
            new JsonResponse(['id' => 'order']);
        }

        
        foreach ($order->getDetailCommandes()->getValues() as $product){
            //$product_object =  $entityManager->getRepository(Produit::class)->findOneByName( $product->getProduit());
            $products_for_stripe[] = [
                'price_data' => [
                  'currency' => 'eur',
                  'unit_amount' => $product->getPrix(),
                  'product_data' => [
                      'name' => $product->getProduit(),
                      //'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                  ],
                ],
                'quantity' => $product->getQuantite(),
            ];
        }

        $products_for_stripe[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $order->getTransporteurPrix(),
              'product_data' => [
                  'name' => $order->getTransporteurName(),
                  'images' => [$YOUR_DOMAIN],
              ],
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey('sk_test_51N9NztKPuEcJO0FFAk72ep2dhf11QSUP98KgI2Nq13C8yzoGqLbqU6CnG2l6mW54Yhwb7QD65xJbqNKdvsophuo500efl2fYiR');    
           
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $products_for_stripe,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
          ]);  

          $order->setStripeSessionId($checkout_session->id);
          $entityManager->flush();
          
          $response = new JsonResponse(['id' => $checkout_session->id]);
          return $response;
    }
}

