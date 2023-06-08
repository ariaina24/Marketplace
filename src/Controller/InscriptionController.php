<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\InscriptionType;

class InscriptionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->CreateForm(InscriptionType::class, $user);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();// Récupérer le rôle sélectionné
        
            // Mettre à jour le champ 'roles' de l'objet User
            $user->setRoles(['client']);

            $password = $encoder -> encodePassword($user,$user->getPassword());
            $user ->setPassword($password);
            
            

            $this-> entityManager ->persist($user);
            $this-> entityManager ->flush();
            return $this->redirectToRoute('connexion');
        }
        return $this->render('inscription/index.html.twig', [
            'form' => $form -> CreateView(),
        ]);
    }
}
