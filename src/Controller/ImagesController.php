<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImagesController extends AbstractController
{
    #[Route('/', name: 'app_images')]
    public function index(): Response
    {
        return $this->render('images/index.html.twig', [
            'title' => 'Votre image de rêve',
        ]);
    }
}
