<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_recette_index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, RecetteRepository $recetteRepository): Response
    // {
    //     $recette = new Recette();
    //     $form = $this->createForm(RecetteType::class, $recette);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Récupérer les régimes et les allergies sélectionnés depuis le formulaire
    //         $selectedRegimes = $form->get('regimes')->getData();
    //         $selectedAllergies = $form->get('allergies')->getData();

    //         // Sauvegarder la recette
    //         $recetteRepository->saveRecette($recette);

    //         // Ajouter la recette à chaque régime sélectionné
    //         foreach ($selectedRegimes as $regime) {
    //             $regime->addRecette($recette);
    //             $recetteRepository->saveRegime($regime);
    //         }

    //         // Ajouter la recette à chaque allergie sélectionnée
    //         foreach ($selectedAllergies as $allergie) {
    //             $allergie->addRecette($recette);
    //             $recetteRepository->saveAllergie($allergie);
    //         }

    //         return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('recette/new.html.twig', [
    //         'recette' => $recette,
    //         'form' => $form,
    //     ]);
    // }
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les régimes et les allergies sélectionnés depuis le formulaire
            $selectedRegimes = $form->get('regimes')->getData();
            $selectedAllergies = $form->get('allergies')->getData();
            // Sauvegarder la recette
            $entityManager->persist($recette);
            $entityManager->flush();
            // Ajouter la recette à chaque régime sélectionné
            foreach ($selectedRegimes as $regime) {
                $regime->addRecette($recette);
                $entityManager->persist($regime);
            }
            // Ajouter la recette à chaque allergie sélectionnée
            foreach ($selectedAllergies as $allergie) {
                $allergie->addRecette($recette);
                $entityManager->persist($allergie);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_recette_index');
        }
        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_recette_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les régimes et les allergies sélectionnés depuis le formulaire
            $selectedRegimes = $form->get('regimes')->getData();
            $selectedAllergies = $form->get('allergies')->getData();

            // Sauvegarder la recette
            $recetteRepository->saveRecette($recette);

            // Ajouter la recette à chaque régime sélectionné
            foreach ($selectedRegimes as $regime) {
                $regime->addRecette($recette);
                $recetteRepository->saveRegime($regime);
            }

            // Ajouter la recette à chaque allergie sélectionnée
            foreach ($selectedAllergies as $allergie) {
                $allergie->addRecette($recette);
                $recetteRepository->saveAllergie($allergie);
            }

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recette->getId(), $request->request->get('_token'))) {
            $recetteRepository->remove($recette, true);
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }
}
