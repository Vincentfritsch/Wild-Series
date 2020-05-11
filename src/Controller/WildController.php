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
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries'
        ]);
    }
    /**
     * @Route("wild/show/{slug}",
     *     methods={"GET"},
     *     requirements={"slug"="[a-z0-9-]+"},
     *     defaults={"slug"=""}, name="wild_show")
     */
    public function show(string $slug )
    {
        return$this->render('wild/show.html.twig', [
            'slug' => !empty($slug) ? ucwords(str_replace('-',' ',$slug)) : 'Aucune série sélectionnée, veuillez choisir une série'
        ]);
    }
}