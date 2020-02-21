<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\EpisodeType;
use App\Services\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/episode")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/", name="episode_index", methods={"GET"})
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('episode/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/{id}/all", name="episodes_index", methods={"GET"})
     * @param int|null $id
     * @return Response
     */
    public function indexAllEpisodes(?int $id): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $id]);

        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $seasons]);

        return $this->render('episode/indexAll.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'episodes' => $episodes
        ]);

    }

    /**
     * @Route("/{id}/new", name="episode_new", methods={"GET","POST"})
     * @param Request $request
     * @param Season $season
     * @param Slugify $slugify
     * @return Response
     */
    public function new(Request $request, Season $season, Slugify $slugify): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $season->addEpisode($episode);

            $episode->setSlug($slugify->generate($episode->getTitle()));

            $entityManager->persist($episode);
            $entityManager->flush();

            $this->addFlash('success', 'L\'épisode a été ajouté avec succès');

            return $this->redirectToRoute('episodes_index', [
                'id' => $episode->getSeason()->getProgram()->getId()
            ]);
        }

        return $this->render('episode/new.html.twig', [
            'season' => $season,
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="episode_show", methods={"GET"})
     * @param Episode $episode
     * @return Response
     */
    public function show(Episode $episode): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="episode_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Episode $episode
     * @param Slugify $slugify
     * @return Response
     */
    public function edit(Request $request, Episode $episode, Slugify $slugify): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episode->setSlug($slugify->generate($episode->getTitle()));

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'épisode a été modifié avec succès');

            return $this->redirectToRoute('episodes_index', [
                'id' => $episode->getSeason()->getProgram()->getId()
            ]);
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="episode_delete", methods={"DELETE"})
     * @param Request $request
     * @param Episode $episode
     * @return Response
     */
    public function delete(Request $request, Episode $episode): Response
    {
        if ($this->isCsrfTokenValid('delete' . $episode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($episode);
            $entityManager->flush();

            $this->addFlash('success', 'L\'épisode a été supprimé avec succès');
        }

        return $this->redirectToRoute('episodes_index', [
            'id' => $episode->getSeason()->getProgram()->getId()
        ]);
    }
}
