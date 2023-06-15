<?php

namespace App\Controller;

use App\Entity\Allergie;
use App\Form\AllergieType;
use App\Repository\AllergieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/allergie')]
class AllergieController extends AbstractController
{
    #[Route('/', name: 'app_allergie_index', methods: ['GET'])]
    public function index(AllergieRepository $allergieRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Configurez le nombre d'avis à afficher par page
    $pageSize = 5;

    // Créer la requête pour récupérer les avis triés par ID croissant
    $query = $allergieRepository->createQueryBuilder('a')
        ->orderBy('a.id', 'ASC')
        ->getQuery();

    // Obtenir les résultats de la requête
    $results = $query->getResult();

    // Paginer les résultats
    $pagination = $paginator->paginate(
        $results,
        $request->query->getInt('page', 1), // Numéro de page par défaut
        $pageSize // Nombre d'éléments par page
    );

    return $this->render('allergie/index.html.twig', [
        'pagination' => $pagination,
    ]);
        // return $this->render('allergie/index.html.twig', [
        //     'allergies' => $allergieRepository->findAll(),
        // ]);
    }

    #[Route('/new', name: 'app_allergie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AllergieRepository $allergieRepository): Response
    {
        $allergie = new Allergie();
        $form = $this->createForm(AllergieType::class, $allergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allergieRepository->save($allergie, true);

            return $this->redirectToRoute('app_allergie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('allergie/new.html.twig', [
            'allergie' => $allergie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_allergie_show', methods: ['GET'])]
    public function show(Allergie $allergie): Response
    {
        return $this->render('allergie/show.html.twig', [
            'allergie' => $allergie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_allergie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Allergie $allergie, AllergieRepository $allergieRepository): Response
    {
        $form = $this->createForm(AllergieType::class, $allergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allergieRepository->save($allergie, true);

            return $this->redirectToRoute('app_allergie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('allergie/edit.html.twig', [
            'allergie' => $allergie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_allergie_delete', methods: ['POST'])]
    public function delete(Request $request, Allergie $allergie, AllergieRepository $allergieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$allergie->getId(), $request->request->get('_token'))) {
            $allergieRepository->remove($allergie, true);
        }

        return $this->redirectToRoute('app_allergie_index', [], Response::HTTP_SEE_OTHER);
    }
}
