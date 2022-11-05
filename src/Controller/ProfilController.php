<?php

namespace App\Controller;

use App\Entity\Messagerie;
use App\Form\DonneesUserType;
use App\Form\MessagerieType;
use App\Repository\MessagerieRepository;
use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('profil/index.html.twig', [
            'reservation' => $reservationRepository->findAll()
        ]);
    }

    #[Route('/new-message', name: 'app_messagerie_user_new', methods: ['GET', 'POST'])]
    public function new(HttpFoundationRequest $request, MessagerieRepository $messagerieRepository,
                        EntityManagerInterface $em): Response
    {
        $message = new Messagerie();
        $form = $this->createForm(MessagerieType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateEnvoi = new DateTime();
            $message->setCreatedAt($dateEnvoi);
            $message->setIsRead(false);
            $message->setSender($this->getUser());
            $em->persist($message);
            $em->flush();
            // à remettre demain
            $messagerieRepository->save($message, true);

            // route de la page d'accueil messagerie
            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('messagerie/new.html.twig', [
            'message' => $message,
            'form' => $form
        ]);
    }

    #[Route('/completer-donnees', name: 'app_donnees_user_add', methods: ['GET', 'POST'])]
    public function donneesAdd(HttpFoundationRequest $request,
                        EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(DonneesUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            // route de la page d'accueil messagerie
            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/donnees.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/profil-voir-messages', name: 'app_profil_show_message')]
    public function userShowMessage(): Response
    {
        return $this->render('profil/messages.html.twig', [

        ]);
    }

}
