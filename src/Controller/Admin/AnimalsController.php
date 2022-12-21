<?php

namespace App\Controller\Admin;

use App\Entity\Animals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/animals', name: 'admin_animals_')]
class AnimalsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('/admin/animals/index.html.twig', [
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('/admin/animals/index.html.twig', [
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Animals $animal): Response
    {
        // On vérifie si l'utilisteur peut éditer avec le Voter
        $this->denyAccessUnlessGranted('ANIMAL_EDIT', $animal);
        return $this->render('/admin/animals/index.html.twig', [
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Animals $animals): Response
    {
        // On vérifie si l'utilisteur peut supprimer avec le Voter
        $this->denyAccessUnlessGranted('ANIMAL_DELETE', $animals);
        return $this->render('/admin/animals/index.html.twig', [
        ]);
    }
}
