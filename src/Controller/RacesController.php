<?php

namespace App\Controller;

use App\Entity\Races;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/races', name: 'races_')]
class RacesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Races $races): Response
    {

        // On va chercher les animaux de la race
        $animals = $races->getAnimals();
        return $this->render('races/list.html.twig', [
            'races' => $races,
            'animals' => $animals,
        ]);
    }
}
