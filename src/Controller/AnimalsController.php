<?php

namespace App\Controller;

use App\Entity\Animals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/animaux', name: 'animals_')]
class AnimalsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('animals/index.html.twig', [
            'controller_name' => 'AnimalsController',
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Animals $animals): Response
    {
        return $this->render('animals/details.html.twig', [
            'animal' => $animals,
        ]);
    }

    
}
