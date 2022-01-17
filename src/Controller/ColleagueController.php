<?php

namespace App\Controller;

use App\Entity\Colleague;
use App\Form\ColleagueType;
use App\Repository\ColleagueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/colleague")
 */
class ColleagueController extends AbstractController
{
    /**
     * @Route("/", name="colleague_index", methods={"GET"})
     */
    public function index(ColleagueRepository $colleagueRepository): Response
    {
        return $this->render('colleague/index.html.twig', [
            'colleagues' => $colleagueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="colleague_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $colleague = new Colleague();
        $colleague->setCreatedAt(new \DateTime('now'));
        $form = $this->createForm(ColleagueType::class, $colleague);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploaded_file = $request->files->get('colleague')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            if($uploaded_file != null) {
                $uploaded_filename = md5(uniqid()) . '.' . $uploaded_file->guessExtension();
                $uploaded_file->move(
                    $uploads_directory,
                    $uploaded_filename
                );
                $colleague->setImage($uploaded_filename);
            }

            //var_dump($uploaded_file); die;

            $entityManager->persist($colleague);
            $entityManager->flush();

            return $this->redirectToRoute('colleague_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('colleague/new.html.twig', [
            'colleague' => $colleague,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="colleague_show", methods={"GET"})
     */
    public function show(Colleague $colleague): Response
    {
        return $this->render('colleague/show.html.twig', [
            'colleague' => $colleague,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="colleague_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Colleague $colleague, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ColleagueType::class, $colleague);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploaded_file = $request->files->get('colleague')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            if($uploaded_file != null) {
                $uploaded_filename = md5(uniqid()) . '.' . $uploaded_file->guessExtension();
                $uploaded_file->move(
                    $uploads_directory,
                    $uploaded_filename
                );
                $colleague->setImage($uploaded_filename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('colleague_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('colleague/edit.html.twig', [
            'colleague' => $colleague,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="colleague_delete", methods={"POST"})
     */
    public function delete(Request $request, Colleague $colleague, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$colleague->getId(), $request->request->get('_token'))) {
            $entityManager->remove($colleague);
            $entityManager->flush();
        }

        return $this->redirectToRoute('colleague_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/sendgreeting/{name}/{email}", name="send_greeting", methods={"GET"})
     */
    public function sendGreeting(MailerInterface $mailer, $name, $email): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($email)
            ->subject('Hi!')
            ->text('Hi '.$name.'! I wanted to say hi after our recent meeting!')
            ->html('<p>Hi '.$name.'! I wanted to say hi after our recent meeting!</p>');

        $mailer->send($email);

        $this->addFlash('success', 'Greeting Email Sent to '.$name.'!');

        return $this->redirectToRoute('colleague_index');
    }
}
