<?php

namespace App\Controller;

use App\Repository\RacesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(RacesRepository $racesRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'races' => $racesRepository->findBy([], ['raceOrder' => 'ASC']),
        ]);
    }
}
