<?php

namespace App\Controller;

use App\Entity\Races;
use App\Repository\AnimalsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/races', name: 'races_')]
class RacesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Races $races, AnimalsRepository $animalsRepository, Request $request): Response
    {


        // on va chercher le numéro de la page dans l'URL
        $page = $request->query->getInt('page', 1);

        // On va chercher les animaux de la race
        $animals = $animalsRepository->findAnimalsPaginated($page, $races->getSlug(), 2);

        return $this->render('races/list.html.twig', [
            'races' => $races,
            'animals' => $animals,
        ]);
    }
}
