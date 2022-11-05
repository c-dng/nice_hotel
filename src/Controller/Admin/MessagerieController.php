<?php

namespace App\Controller\Admin;

use App\Entity\Messagerie;
use App\Form\MessagerieType;
use App\Repository\MessagerieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/messagerie')]
class MessagerieController extends AbstractController
{
    #[Route('/', name: 'app_messagerie_index', methods: ['GET'])]
    public function index(MessagerieRepository $messagerieRepository): Response
    {
        return $this->render('messagerie/index.html.twig', [
            'messageries' => $messagerieRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_messagerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessagerieRepository $messagerieRepository,
                        EntityManagerInterface $em): Response
    {
        $message = new Messagerie();
        $form = $this->createForm(MessagerieType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($this->getUser());
            // $message->setCreatedAt(new \DateTime('now'));
            $em->persist($message);
            $em->flush();
            // à remettre demain
            $messagerieRepository->save($message, true);

            // route de la page d'accueil messagerie
            return $this->redirectToRoute('app_messagerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('messagerie/new.html.twig', [
            'message' => $message,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_messagerie_show', methods: ['GET'])]
    public function show(Messagerie $messagerie): Response
    {
        return $this->render('messagerie/show.html.twig', [
            'messagerie' => $messagerie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_messagerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Messagerie $messagerie, MessagerieRepository $messagerieRepository): Response
    {
        $form = $this->createForm(MessagerieType::class, $messagerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messagerieRepository->save($messagerie, true);

            return $this->redirectToRoute('app_messagerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('messagerie/edit.html.twig', [
            'messagerie' => $messagerie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_messagerie_delete', methods: ['POST'])]
    public function delete(Request $request, Messagerie $messagerie, MessagerieRepository $messagerieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$messagerie->getId(), $request->request->get('_token'))) {
            $messagerieRepository->remove($messagerie, true);
        }

        return $this->redirectToRoute('app_messagerie_index', [], Response::HTTP_SEE_OTHER);
    }
}
