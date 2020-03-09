<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package App\Controller
 * @Route("/my-profile")
 */
class ProfileController extends AbstractController
{

    /**
     * @Route("/{id}", name="profile_index")
     * @param int|null $id
     * @return Response
     */
    public function index(?int $id): Response
    {
        $profile = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id' => $id]);

        return $this->render('profile.html.twig', [
            'profile' => $profile
        ]);
    }

}