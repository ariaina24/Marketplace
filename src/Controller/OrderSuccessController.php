<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Entity\Order;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function stripe(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Commande::class)->findOneByStripeSessionId($stripeSessionId);
        //$detailCommande = $this->entityManager->getRepository(DetailCommande::class)->findOneByCommande($order);
        //$product = $this->entityManager->getRepository(Produit::class)->findOneByNom($detailCommande->getProduit());
        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        //$calcul = $product->getQuantite()-$detailCommande->getQuantite();

        /* $product->setQuantite($calcul);
        $this->entityManager->persist($product); */

        

        if (!$order->isPaye()){
            // Vider la session "cart"
            $cart->remove();

            // Modifier le statut isPaid de notre commande en mettant 1
            $order->setPaye(1);
            $this->entityManager->flush();

        }

        // Afficher les quelques informations de la commande de l'utilisateur 
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }

}
