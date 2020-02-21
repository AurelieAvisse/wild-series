<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\SeasonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/season")
 */
class SeasonController extends AbstractController
{

    /**
     * @Route("/", name="season_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $seasons = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('season/index.html.twig', [
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route("/{id}/all", name="seasons_index", methods={"GET"})
     * @param int|null $id
     * @return Response
     */
    public function indexAllSeasons(?int $id): Response
    {

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $id]);

        return $this->render('season/indexAll.html.twig', [
            'seasons' => $seasons,
            'program' => $program
        ]);
    }

    /**
     * @Route("/{id}/new", name="season_new", methods={"GET","POST"})
     * @param Request $request
     * @param Program $program
     * @return Response
     */
    public function new(Request $request, Program $program): Response
    {
        $season = new Season();
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $program->addSeason($season);

            $entityManager->persist($season);
            $entityManager->flush();

            $this->addFlash('success', 'La saison a été ajoutée avec succès');

            return $this->redirectToRoute('seasons_index', [
                'id' => $program->getId()
            ]);
        }

        return $this->render('season/new.html.twig', [
            'program' => $program,
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="season_show", methods={"GET"})
     * @param Season $season
     * @return Response
     */
    public function show(Season $season): Response
    {
        return $this->render('season/show.html.twig', [
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="season_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Season $season
     * @return Response
     */
    public function edit(Request $request, Season $season): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La saison a été modifiée avec succès');

            return $this->redirectToRoute('seasons_index', [
                'id' => $season->getProgram()->getId()
            ]);
        }

        return $this->render('season/edit.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="season_delete", methods={"DELETE"})
     * @param Request $request
     * @param Season $season
     * @return Response
     */
    public function delete(Request $request, Season $season): Response
    {
        if ($this->isCsrfTokenValid('delete' . $season->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($season);
            $entityManager->flush();

            $this->addFlash('success', 'La saison a été supprimée avec succès');
        }

        return $this->redirectToRoute('seasons_index', [
            'id' => $season->getProgram()->getId()
        ]);
    }
}
