<?php


namespace App\Controller;

use App\Form\ProgramSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Entity\Program;

class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("/", name="app_index")
     * @param ProgramRepository $program
     * @return Response
     */
    public function index(ProgramRepository $program)
    {
        $programs = $program->findAllWithCategoriesAndActors();

        return $this->render('index.html.twig', [
            'programs' => $programs
        ]);
    }

}