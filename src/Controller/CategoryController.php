<?php

namespace App\Controller;

use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/category/add", name="category_add")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $newCategory = $this->getDoctrine()->getManager();
            $newCategory->persist($data);
            $newCategory->flush();
            return $this->redirectToRoute('wild_show_categories');
        }
        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
