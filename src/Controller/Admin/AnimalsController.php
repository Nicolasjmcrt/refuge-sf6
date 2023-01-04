<?php

namespace App\Controller\Admin;

use App\Entity\Animals;
use App\Form\AnimalsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/animals', name: 'admin_animals_')]
class AnimalsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('/admin/animals/index.html.twig', [
        ]);
    }

    // #[Route('/add', name: 'add')]
    // public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');

    //     // On créé un nouvel animal
    //     $animal = new Animals();

    //     // On créé le formulaire
    //     $animalForm = $this->createForm(AnimalsFormType::class, $animal);

    //     // On récupère les données du formulaire
    //     $animalForm->handleRequest($request);

    //     // On vérifie si le formulaire est soumis et valide
    //     if ($animalForm->isSubmitted() && $animalForm->isValid()) {
    //         // On génère le slug
    //         $slug = $slugger->slug($animal->getName())->lower();
    //         $animal->setSlug($slug);

    //         // On enregistre l'animal
    //         $em->persist($animal);
    //         $em->flush();

    //         $this->addFlash('success', 'L\'animal a bien été ajouté !');

    //         // On redirige vers la page de l'animal
    //         return $this->redirectToRoute('admin_animals_index');
    //     }

    //     return $this->render('/admin/animals/add.html.twig', [
    //         'animalForm' => $animalForm->createView()
    //     ]);
    // }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Animals $animal, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // On vérifie si l'utilisteur peut éditer avec le Voter
        $this->denyAccessUnlessGranted('ANIMAL_EDIT', $animal);
        
        // On créé le formulaire
        $animalForm = $this->createForm(AnimalsFormType::class, $animal);

        // On récupère les données du formulaire
        $animalForm->handleRequest($request);

        // On vérifie si le formulaire est soumis et valide
        if ($animalForm->isSubmitted() && $animalForm->isValid()) {
            // On génère le slug
            $slug = $slugger->slug($animal->getName())->lower();
            $animal->setSlug($slug);

            // On enregistre l'animal
            $em->persist($animal);
            $em->flush();

            $this->addFlash('success', 'L\'animal a bien été modifié !');
            
            // On redirige vers la page de l'animal
            return $this->redirectToRoute('admin_animals_index');
        }

        return $this->render('/admin/animals/edit.html.twig', [
            'animalForm' => $animalForm->createView()
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
