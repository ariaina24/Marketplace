<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\AjoutProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageVendeurController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/page-des-vendeurs", name="page_vendeur")
     */
    public function index(): Response
    {
        // dd($this->getUser()->getId());
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
        foreach ($produits as $produit) {
            $vendeur = $produit->getVendeur();
            $vendeurId = $vendeur ? $vendeur->getId() : null;

            if ($vendeurId == $this->getUser()->getId()){
                $produitsUtilisateur[] = $produit;
            }
        }
        return $this->render('page_vendeur/index.html.twig',[
            'produits' => $produitsUtilisateur
        ]);
    }

}
