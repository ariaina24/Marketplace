<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()->getAdresses()->getValues())
        {
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('commande/index.html.twig',[
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/recap", name="order_recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $date = new DateTime();
            $carriers =$form->get('transporteur')->getData();
            $delivery =$form->get('adresse')->getData();
            $delivery_content = $delivery->getNom().' '.$delivery->getPrenom();
            $delivery_content .= '</br>'.$delivery->getTelephone();
            if ($delivery->getSociete()){
                $delivery_content .= '</br>'.$delivery->getSociete();
            }

            $delivery_content .= '</br>'.$delivery->getAdresse();
            $delivery_content .= '</br>'.$delivery->getCodePostal().' '.$delivery->getVille();
            $delivery_content .= '</br>'.$delivery->getPays(); 
            // Enregistrer ma commande Order()
            $order = new Commande();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setUser($this->getUser());
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setDateCommande($date);
            $order->setTransporteurName($carriers->getNom());
            $order->setTransporteurPrix($carriers->getPrix());
            $order->setLivraison($delivery_content);
            $order->setPaye(0);

            $this->entityManager->persist($order);

            // Enregistrer mes produits OrderDetails()
            foreach ($cart->getFull() as $product){
                $orderDetails = new DetailCommande();
                $orderDetails->setCommande($order);
                $orderDetails->setProduit($product['product']->getNom());
                $orderDetails->setQuantite($product['quantity']);
                $orderDetails->setPrix($product['product']->getPrix());
                $orderDetails->setTotal($product['product']->getPrix()*$product['quantity']);
                $this->entityManager->persist($orderDetails);
            }


            $this->entityManager->flush();

            return $this->render('commande/add.html.twig',[
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()
            ]);

        }
        return $this->redirectToRoute('cart');
    }
}
