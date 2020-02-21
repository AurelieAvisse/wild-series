<?php

namespace App\Controller;

use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorIndexController extends AbstractController
{

    /**
     * @Route("/actor/{slug}", name="actor_index", methods={"GET"})
     * @param Actor $actor
     * @return Response
     */
    public function index(Actor $actor): Response
    {
        return $this->render('actor/index.html.twig', [
            'actor' => $actor,
        ]);
    }

    /**
     * @Route("/{id}", name="actor_delete", methods={"DELETE"})
     * @param Request $request
     * @param Actor $actor
     * @return Response
     */
    public function delete(Request $request, Actor $actor): Response
    {
        if ($this->isCsrfTokenValid('delete' . $actor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actor);
            $entityManager->flush();

            $this->addFlash('success', 'L\'acteur a été supprimé avec succès');
        }

        return $this->redirectToRoute('actor_new');
    }

}