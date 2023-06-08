<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Classe\Search;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageClientController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/page-des-client", name="page_client")
     */
    public function index(Request $request): Response
    {
        // dd($products);     


        $search= new Search();
        $form= $this->createForm(SearchType::class, $search);
        
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $products = $this->entityManager->getRepository(Produit::class)->findWithSearch($search);
            // dd($search);
        }else{
            
            $products = $this->entityManager->getRepository(Produit::class)->findAll();
        }
        return $this->render('page_client/index.html.twig',[
            'products'=>$products,
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/compte/page-des-client/{slug}", name="app_prodpage_client_show")
     */
    public function show($slug): Response
    {
        $product = $this->entityManager->getRepository(Produit::class)->findOneByslug($slug);
        // dd($product);     
        if(!$product)
        {
            return $this->redirectToRoute('app_products');
        }

        return $this->render('page_client/show.html.twig',[
            'product'=>$product
        ]);
    }
}

