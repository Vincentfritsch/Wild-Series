<?php

namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Service\Slugify;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program_index", methods={"GET"})
     * @param ProgramRepository $programRepository
     * @return Response
     */
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render('program/index.html.twig', [
            'programs' => $programRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="program_new", methods={"GET","POST"})
     * @param Request $request
     * @param Slugify $slugify
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();
            $this->addFlash('success', 'The new program has been created');
            $email = (new TemplatedEmail())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_from'))
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->htmlTemplate('program/email/notification.html.twig')
                ->context([
                    'program'=> $program,
                  ]);
            ;

            $mailer->send($email);
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="program_show", methods={"GET"})
     * @param Program $program
     * @return Response
     */
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
 * @Route("/{slug}/edit", name="program_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Program $program
     * @return Response
     */
    public function edit(Request $request, Program $program): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        $slugify = new Slugify();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->flush();
            $this->addFlash('success', 'The program has been updated');
            return $this->redirectToRoute('program_index');
        }


        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="program_delete", methods={"DELETE"})
     * @param Request $request
     * @param Program $program
     * @return Response
     */
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
            $this->addFlash('danger', 'The program has been succefully deleted');
        }

        return $this->redirectToRoute('program_index');
    }

    /**
     * @Route("/{id}/watchlist", name="program_watchlist", methods={"GET",
     * "POST"})
     * @param Program $program
     */
    public function addToWatchlist(Program $program)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->addWatchlist($program);
        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('wild_show', [
            'slug' => $program->getSlug()
        ]);
    }
}
