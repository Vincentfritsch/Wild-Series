<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @return Response
     * @Route("/wild", name="wild_index")
     */
    public function index(): Response
    {
        return$this->render('wild/index.html.twig', [
            'website' => 'Wild Séries'
        ]);
    }
}