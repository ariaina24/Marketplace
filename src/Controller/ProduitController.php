<?php

namespace App\Controller;

use App\Entity\Produit;
// use App\Classe\Search;
// use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compte/page", name="app_products")
     */
    public function index(Request $request): Response
    {
        // dd($products);     


        // $search= new Search();
        // $form= $this->createForm(SearchType::class, $search);
        
        
        $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid())
        // {
        //     $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        //     // dd($search);
        // }else{
            
            $products = $this->entityManager->getRepository(Produit::class)->findAll();
            dd($products);
        // }
        return $this->render('page_client/index.html.twig',[
            'products'=>$products,
            // 'form' =>$form->createView()
        ]);
    }
}
