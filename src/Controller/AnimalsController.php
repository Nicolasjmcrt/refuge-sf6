<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Animals;
use App\Form\AnimalsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/animaux', name: 'animals_')]
class AnimalsController extends AbstractController
{
    
    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_USER');

        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion et affiche un message

        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté pour déclarer la perte d\'un animal !');
            return $this->redirectToRoute('app_login');
        }

        // On créé un nouvel animal
        $animal = new Animals();
        
        // On créé le formulaire
        $animalForm = $this->createForm(AnimalsFormType::class, $animal);
        
        // On récupère les données du formulaire
        $animalForm->handleRequest($request);
        
        // On vérifie si le formulaire est soumis et valide
        if ($animalForm->isSubmitted() && $animalForm->isValid()) {
            // On génère le slug
            $slug = $slugger->slug($animal->getName())->lower();
            $animal->setSlug($slug);
            // On ajoute l'utilisateur actuellement connecté
            $animal->setUser($this->getUser());
            
            // On enregistre l'animal
            $em->persist($animal);
            $em->flush();
            
            $this->addFlash('success', 'L\'animal a bien été ajouté !');
            
            // On redirige vers la page de l'animal
            return $this->redirectToRoute('main');
        }
        
        return $this->render('/animals/add.html.twig', [
            'animalForm' => $animalForm->createView()
        ]);
    }
    
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
