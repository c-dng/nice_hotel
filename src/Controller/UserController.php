<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\RechercheChambreType;
use App\Repository\ChambreRepository;
use App\Repository\ReservationRepository;
use App\Services\RechercheChambre;
use App\Services\SelectChambreDispo;
use App\Services\Tools;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request, SelectChambreDispo $selectChambreDispo): Response
    {
         // je crée l'objet rechercheChambre (coquille vide)
         $rechercheChambre = new RechercheChambre();
         // je le lie au formulaire
         $form = $this->createForm(RechercheChambreType::class, $rechercheChambre);
         // je met le formulaire à l'écoute de la request
         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) {
             $dateArrive = $form->get('date_arrive')->getData();
             // on vérifie si on récupère bien les dates
             // dd($dateArrive);
             $dateDepart = $form->get('date_depart')->getData();
             $category = $form->get('category')->getData();
             $interval = $dateArrive->diff($dateDepart);
             $tableauChambre = $selectChambreDispo->getChambresDispo($dateArrive, $dateDepart, $category);
             // dd($tableauChambre);
             // on l'envoie à la view
             return $this->render('user/index.html.twig', [
                 'form' => $form->createView(),
                 // 'category' => $categoryRepository->findAll(),
                 'chambresDispo' => $tableauChambre,
                 'nb_jour' => $interval->format('%a'),
                 'date_arrive' => $dateArrive,
                 'date_depart' => $dateDepart
             ]);
         }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route("/new-reservation/{id}/{date_arrive}/{date_depart}/", name: 'app_user_reservation')]
    public function newReservation($id, DateTime $date_arrive,
                                        DateTime $date_depart,
                                        ReservationRepository $reservationRepository,
                                        ChambreRepository $chambreRepository,
                                        Tools $tools)
    {
        // grâce à $tools on va récupérer les infos user (Nom + Prénom + Tél)
        $user = $tools->getUser();
        $user_nom = $user->getNom();
        $user_prenom = $user->getPrenom();
        $tel = $user->getTel();
        
        if($user){
            if(!$user_nom || !$user_prenom || !$tel){
                // j'envoi un fomulaire pour compléter les données 
                // dd($user_nom);
                return $this->redirectToRoute('app_donnees_user_add');
            }
          } else {
              return $this->redirect('app_login');
          }
        
        $reservation = new Reservation();
        // je set la date d'arrivée
        $reservation->setDateArrive($date_arrive);

        // je veux fixer l'heure du checkin à 10h00
        date_Time_Set($date_arrive, 10, 0);

        // je vérifie que $date_arrive est bien à 10h00
        // dd($date_arrive);

        // je set la date de départ
        $reservation->setDateDepart($date_depart);

        // je veux fixer l'heure du checkout à 15h00
        date_Time_Set($date_depart, 15, 0);

        // je vérifie que $date_depart est bien à 15h00
        // dd($date_depart);

        $date_resa = new DateTime();
        // dd($date_resa);

        $reservation->setDateResa($date_resa);

        $chambre = $chambreRepository->find($id);

        $reservation->setChambre($chambre);
        
        // je set le total
        $reservation->setTotal($tools->getTotalreservation($chambre, $date_arrive, $date_depart));
        
        // je récupère l'user connecté
        $user = $this->getUser();

        // je set le user de la réservation
        $reservation->setUser($user);

        $reservation->setTarifUnit($chambre->getTarif());
        // je set le paiement
        $reservation->setPaiement(false);
        // je set la chambre
        $reservation->setChambre($chambre);
        $reservation->setNom($tools->getUser()->getNom());
        // dd($reservation);
        $reservationRepository->save($reservation, true);

        $id_resa = $reservation->getId();

        // essai camille pr informer l'user que la réservation a bien été effectuée
        $this->addFlash('success', 'Merci, votre réservation a bien été effectuée.');

        return $this->redirectToRoute('app_profil', [
            'id' => $id_resa
        ]);
    }
}
