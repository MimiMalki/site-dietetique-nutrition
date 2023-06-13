<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/galleryRecette')]
class GalleryRecetteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/gallery_recette', name: 'app_gallery_recette')]
    public function index(): Response
    {
        return $this->render('gallery_recette/index.html.twig', [
            'controller_name' => 'GalleryRecetteController',
        ]);
    }
    #[Route('/', name: 'app_gallery_recette')]
    public function view(RecetteRepository $recetteRepository, AvisRepository $avisRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté (le patient)
        $patient = $this->getUser();
        $recettes = $recetteRepository->findAll();
        if ($patient instanceof User) {
            // Filtrer les recettes selon le régime et les allergies du patient
            $recettes = $this->filtrerRecettesParRegimeEtAllergenes($patient);
        } 
        // else {
        //     // Récupérer toutes les recettes accessibles à tous les visiteurs
        //     $recettes = $recetteRepository->findBy(['accessiblePatient' => false]);
        // }
        // Afficher les recettes dans la vue appropriée
        return $this->render('gallery_recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }
    #[Route('/{id}', name: 'app_gallery_recette_show', methods: ['GET', 'POST'])]
    public function show(Recette $recette, Request $request, RecetteRepository $recetteRepository): Response
    {

        // Récupérer l'utilisateur connecté (le patient)
        $patient = $this->getUser();

        // Créer une instance de l'entité Avis
        $avis = new Avis();

        // Créer le formulaire pour l'avis
        $form = $this->createForm(AvisType::class, $avis);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer l'avis au patient
            $avis->setUser($patient);
            // Associer l'avis au patient
            $avis->setRecette($recette);
            // Enregistrer l'avis en base de données
            $this->entityManager->persist($avis);
            $this->entityManager->flush();
        }
        // Récupérer l'utilisateur connecté (le patient)
        $patient = $this->getUser();
        // $recettes = $recetteRepository->findAll();
        if ($patient instanceof User) {
            // Filtrer les recettes selon le régime et les allergies du patient
            $recettes = $this->filtrerRecettesParRegimeEtAllergenes($patient);
        } else {
            // Récupérer toutes les recettes accessibles à tous les visiteurs
            $recettes = $recetteRepository->findBy(['accessiblePatient' => false]);
        }
        // return $this->render('gallery_recette/show.html.twig', [
        //     'recette' => $recette,
        //     'form' => $form->createView(),

        // 
        // 
        // Get other recipes that come after the current recipe
        $otherRecipes = $recetteRepository->findRecipesAfter($recette);

        return $this->render('gallery_recette/show.html.twig', [
            'recette' => $recette,
            'recettes' => $recettes,
            'form' => $form->createView(),
            'otherRecipes' => $otherRecipes,
        ]);
    }
    public function filtrerRecettesParRegimeEtAllergenes(User $patient)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select('r')
            ->from('App\Entity\Recette', 'r')
            ->join('r.regimes', 'regime')
            ->join('regime.user', 'user')
            ->leftJoin('user.allergies', 'allergie')
            ->where('user = :patient')
            ->setParameter('patient', $patient);

        // Filtrage par régime alimentaire
        foreach ($patient->getRegimes() as $regime) {
            $queryBuilder
                ->andWhere(':regime MEMBER OF r.regimes')
                ->setParameter('regime', $regime);
        }

        // Filtrage par allergies
        foreach ($patient->getAllergies() as $allergie) {
            $queryBuilder
                ->andWhere(':allergie MEMBER OF r.allergies')
                ->setParameter('allergie', $allergie);
        }

        $recettes = $queryBuilder->getQuery()->getResult();

        return $recettes;
    }
    // #[Route('/', name: 'app_gallery_recette', methods: ['GET', 'POST'])]
    // public function viewall(Request $request): Response
    // {
    //     // Récupérer l'utilisateur connecté (le patient)
    //     $patient = $this->getUser();

    //     // Créer une instance de l'entité Avis
    //     $avis = new Avis();

    //     // Créer le formulaire pour l'avis
    //     $form = $this->createForm(AvisType::class, $avis);

    //     // Gérer la soumission du formulaire
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Associer l'avis au patient
    //         $avis->setUser($patient);

    //         // Enregistrer l'avis en base de données
    //         $this->entityManager->persist($avis);
    //         $this->entityManager->flush();

    //         // Rediriger vers la page de recette avec un message de succès
    //         $this->addFlash('success', 'Votre avis a été enregistré avec succès.');

    //         return $this->redirectToRoute('app_gallery_recette');
    //     }

    //     // Afficher la page de recette avec le formulaire d'avis
    //     return $this->render('gallery_recette/index.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
    // #[Route('/{id}/avis', name: 'app_gallery_recette_submit_avis', methods: ['POST'])]
    // public function submitAvis(Request $request, Recette $recette): Response
    // {
    //     // Récupérer l'utilisateur connecté (le patient)
    //     $patient = $this->getUser();

    //     // Créer une instance de l'entité Avis
    //     $avis = new Avis();

    //     // Créer le formulaire pour l'avis
    //     $form = $this->createForm(AvisType::class, $avis);

    //     // Gérer la soumission du formulaire
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Associer l'avis au patient
    //         $avis->setUser($patient);

    //         // Enregistrer l'avis en base de données
    //         $this->entityManager->persist($avis);
    //         $this->entityManager->flush();

    //         // Rediriger vers la page de recette avec un message de succès
    //         $this->addFlash('success', 'Votre avis a été enregistré avec succès.');

    //         return $this->redirectToRoute('app_gallery_recette');
    //     }

    //     // Afficher la page de recette avec le formulaire d'avis
    //     return $this->render('gallery_recette/index.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
}
