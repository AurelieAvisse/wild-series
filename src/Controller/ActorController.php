<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Services\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor")
 */
class ActorController extends AbstractController
{

    /**
     * @Route("/new", name="actor_new", methods={"GET","POST"})
     * @param Request $request
     * @param ObjectManager $em
     * @param Slugify $slugify
     * @return Response
     */
    public function new(Request $request, ObjectManager $em, Slugify $slugify): Response
    {

        $actor = new Actor();

        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();

        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actor = $form->getData();

            $actor->setSlug($slugify->generate($actor->getName()));

            $em->persist($actor);
            $em->flush();

            $this->addFlash('success', 'La catégorie a été ajoutée avec succès');

            return $this->redirectToRoute('actor_new');
        }

        return $this->render('actor/new.html.twig', [
            'actors' => $actors,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/{slug}/edit", name="actor_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Actor $actor
     * @param Slugify $slugify
     * @return Response
     */
    public function edit(Request $request, Actor $actor, Slugify $slugify): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actor->setSlug($slugify->generate($actor->getName()));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('actor_new');
        }

        return $this->render('actor/edit.html.twig', [
            'actor' => $actor,
            'form' => $form->createView(),
        ]);
    }


}
