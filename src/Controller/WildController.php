<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CategoryType;
use App\Form\ProgramSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function preg_replace;
use function strip_tags;
use function ucwords;
use function var_dump;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @param Request $request
     * @return Response A response instance
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $formSearch = $this->createForm(
            ProgramSearchType::class);
        $formSearch->handleRequest($request);
        $serie = "";

        if ($formSearch->isSubmitted())
        {
            $data = $formSearch->getData();

            $program = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findOneBy(['title'=>$data]);
            if (!$program) {
               $serie = "Il n'y a aucune série nommée : \" $data[searchField] \"";

            }
            else { $seasons = $program->getSeasons();
                return $this->render('wild/show.html.twig', [
                'slug' => $program->getTitle(),
                'program' => $program,
                'seasons' => $seasons
            ]);
                }
        }

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
            'serie' => $serie,
            'programs' => $programs,
            'formSearch' =>$formSearch->createView()
        ]);
    }
    /**
     * Getting a program with a formatted slug title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}",
     *     methods={"GET"},
     *     defaults={"slug" = null},
     *     name="show")
     * @return Response
     */
    public function showByProgram(?string $slug ): Response
    {
        if (!$slug) {
            throw $this->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        //$slug = preg_replace('/-/', ' ', ucwords(trim(strip_tags($slug)),
        // "-"));

        $program = $this->getDoctrine()->getRepository(Program::class)
            ->findOneBy(['slug' => $slug]);

        if (!$program) {
            throw $this->createNotFoundException('No program with ' .$slug.' title, found in program\'s table.');
        }

        $seasons = $program->getSeasons();
        return $this->render('wild/show.html.twig', [
            'slug' => $slug,
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * Getting 3 programs orderby desc
     *
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

    /**
     * @return Response
     * @Route("/showcategory", name = "show_categories")
     */
    public function showCategory()
    {   $categories = $this->getDoctrine()->getRepository(Category::class)
                            ->findAll();
         return $this->render('wild/showcategory.html.twig', [
             'categories' => $categories
         ]);
    }
    /**
     * @param int $id
     * @return Response
     * @Route("/season/{id}", name = "season")
     */
    public function showBySeason(int $id): Response
    {
        $season = $this->getDoctrine()
                        ->getRepository(Season::class)
                        ->findOneBy(['id'=>$id]);

        $serie = $season->getProgram();

        $episodes = $season->getEpisodes();

        return $this->render('wild/season.html.twig', [
            'saison' => $season,
            'serie' => $serie,
            'episodes' => $episodes,
        ]);
    }

    /**
     * @Route("/episode/{slug}", name="episode")
     * @param Episode $episode
     * @return Response
     *
     */

     public function showEpisode(Episode $episode): Response
    {

        $season = $episode->getSeason();
        $comments = $episode->getComments();
       ;
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [

            'episode'  => $episode,
            'season'   => $season,
            'program'  => $program,
            'comments' => $comments
        ]);
    }
}