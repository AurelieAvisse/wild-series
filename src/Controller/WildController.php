<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{

    // Méthode qui affiche tous les programmes
    /**
     * @Route("/wild", name="wild_index")
     * @return Response
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render('Wild/index.html.twig', [
            'programs' => $programs,
            'categories' => $categories
        ]);
    }

    // Méthode qui affiche un épisode

    /**
     * @Route("/wild/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="wild_show")
     * @param string|null $slug
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw  $this->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = str_replace("-", " ", $slug);
        $slug = ucwords($slug);

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException('No program with ' . $slug . ' title, found in program\'s table.');
        }

        return $this->render('Wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    // Méthode qui affiche les programmes par catégorie

    /**
     * @Route("/category/{categoryName}", defaults={"categoryName" = null}, name="show_category")
     * @param string|null $categoryName
     * @return Response
     */
    public function showByCategory(?string $categoryName): Response
    {
        if (!$categoryName) {
            throw  $this->createNotFoundException('No category has been sent to find a program in program\'s table.');
        }
        $categoryName = str_replace("-", " ", $categoryName);
        $categoryName = ucwords($categoryName);
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category]);
        return $this->render('Wild/category.html.twig', [
            'category' => $category,
            'categoryName' => $categoryName,
            'programs' => $programs
        ]);
    }

    // Méthodes qui affiche les saisons par programmes

    /**
     * @Route("/program/{programName}", defaults={"programName" = null}, name="show_program")
     * @param string|null $programName
     * @return Response
     */
    public function showByProgram(?string $programName): Response
    {
        if (!$programName) {
            throw  $this->createNotFoundException('No program has been sent to find a program in program\'s table.');
        }

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => $programName]);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);

        return $this->render('Wild/program.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'programName' => $programName,

        ]);
    }

    /**
     * @Route("/season/{id}", defaults={"id" = null}, name="show_season")
     * @param int|null $id
     * @return Response
     */
    public function showBySeason(?int $id): Response
    {
        if (!$id) {
            throw  $this->createNotFoundException('No season has been sent to find a program in program\'s table.');
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);

        /** @var Season $season */

        $program = $season->getProgram();

        $episodes = $season->getEpisodes();


        return $this->render('Wild/season.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
            'id' => $id,
        ]);
    }

}