<?php


namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

}