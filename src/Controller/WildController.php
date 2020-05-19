<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function preg_replace;
use function strip_tags;
use function ucwords;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends
    \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @return Response A response instance
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
                        ->getRepository(Program::class)
                        ->findAll();
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s tables.'
            );
        }
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
            'programs' => $programs
        ]);
    }
    /**
     * Getting a program with a formatted slug title
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9- ]+$>}",
     *     methods={"GET"},
     *     defaults={"slug" = null},
     *     name="show")
     * @return Response
     */
    public function show(?string $slug ): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        $slug = preg_replace('/-/', ' ', ucwords(trim(strip_tags($slug)), "-"));

        $program = $this->getDoctrine()->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException('No program with ' .$slug.' title, found in program\'s table.');
        }
        return $this->render('wild/show.html.twig', [
            'slug' => $slug,
            'program' => $program
        ]);
    }

    /**
     * @param string $categoryName
     * @return Response
     * @Route("/category/{categoryName}", name = "show_category")
     */
    public function showByCategory(string $categoryName): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy( ['name'=> $categoryName]);
        $series = $this->getDoctrine()->getRepository(Program::class)
            ->findBy(
                ['category' => $categories],
                 ['id' => 'DESC'],
                 3
            );

        return $this->render('wild/category.html.twig', [
            'series' => $series

        ]);
    }
}