<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Produit::class)->findAll();
        
            
        return $this->render('base.html.twig',[
            'products'=>$products
        ]);
    }
}
