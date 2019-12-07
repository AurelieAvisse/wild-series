<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/category", name="category_new", methods={"GET","POST"})
     * @param Request $request
     * @param ObjectManager $em
     * @return Response
     */
    public function add(Request $request, ObjectManager $em): Response
    {

        $category = new Category();

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie a été ajoutée avec succès');

            return $this->redirectToRoute('category_new');
        }

        return $this->render('Wild/form/category/new.html.twig', [
            'categories' => $categories,
            'form' => $form->createView()
        ]);

    }

}