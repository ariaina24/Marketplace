<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AirtelSuccessController extends AbstractController
{
    /**
     * @Route("/commande/airtel-success", name="airtel_success")
     */
    public function index(): Response
    {
        return $this->render('airtel_success/index.html.twig');
    }
}
