<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContacteController extends AbstractController
{
    #[Route('/contacte', name: 'app_contacte')]
    public function index(): Response
    {
        return $this->render('contacte/index.html.twig', [
            'controller_name' => 'ContacteController',
        ]);
    }
}
