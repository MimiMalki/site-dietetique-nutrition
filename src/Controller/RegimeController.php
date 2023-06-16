<?php

namespace App\Controller;

use App\Entity\Regime;
use App\Form\RegimeType;
use App\Repository\RegimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/regime')]
class RegimeController extends AbstractController
{
    #[Route('/', name: 'app_regime_index', methods: ['GET'])]
    public function index(RegimeRepository $regimeRepository, Request $request, PaginatorInterface $paginator): Response
    {
          // Configurez le nombre d'avis à afficher par page
    $pageSize = 5;

    // Créer la requête pour récupérer les avis triés par ID croissant
    $query = $regimeRepository->createQueryBuilder('a')
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

    return $this->render('regime/index.html.twig', [
        'pagination' => $pagination,
    ]);
        // return $this->render('regime/index.html.twig', [
        //     'regimes' => $regimeRepository->findAll(),
        // ]);
    }

    #[Route('/new', name: 'app_regime_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RegimeRepository $regimeRepository): Response
    {
        $regime = new Regime();
        $form = $this->createForm(RegimeType::class, $regime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $regimeRepository->save($regime, true);

            return $this->redirectToRoute('app_regime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('regime/new.html.twig', [
            'regime' => $regime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_regime_show', methods: ['GET'])]
    public function show(Regime $regime): Response
    {
        return $this->render('regime/show.html.twig', [
            'regime' => $regime,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_regime_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Regime $regime, RegimeRepository $regimeRepository): Response
    {
        $form = $this->createForm(RegimeType::class, $regime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $regimeRepository->save($regime, true);

            return $this->redirectToRoute('app_regime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('regime/edit.html.twig', [
            'regime' => $regime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_regime_delete', methods: ['POST'])]
    public function delete(Request $request, Regime $regime, RegimeRepository $regimeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$regime->getId(), $request->request->get('_token'))) {
            $regimeRepository->remove($regime, true);
        }

        return $this->redirectToRoute('app_regime_index', [], Response::HTTP_SEE_OTHER);
    }
}
