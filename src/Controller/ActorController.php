<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActorController
 * @package App\Controller
 * @Route("/actor")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("/", name="actor")
     * @return Response
     */
    public function index()
    {
        $actors = $this->getDoctrine()
                        ->getRepository(Actor::class)
                        ->findAll();
        return $this->render('actor/index.html.twig', [
            'actors' => $actors,
        ]);
    }

    /**
     * @Route("/{id}", name="actor_show", methods={"GET"})
     * @param Actor $actor
     * @return Response
     */
    public function show(Actor $actor): Response
    {

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,

        ]);
    }
}
