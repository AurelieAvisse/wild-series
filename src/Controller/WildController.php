<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{

    /**
     * @Route("/wild/show/{slug}", requirements={"slug": "[a-z0-9\-]*"}, name="wild_index")
     * @param string $slug
     * @return Response
     */
    public function show(string $slug): Response
    {
        $slug = str_replace("-", " ", $slug);
        $slug = ucwords($slug);

        return $this->render('Wild/show.html.twig', [
            'slug' => $slug,
        ]);
    }

}