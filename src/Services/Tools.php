<?php

namespace App\Services;

use App\Entity\Chambre;
use App\Entity\Reservation;
use App\Entity\User;
use DateTime;
use Symfony\Component\Security\Core\Security;

class Tools
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return User|null
     */
    //  on met : User pour lui dire la sortie, c'est soit un objet user ou bien un null
    public function getUser(): ?User {
        
        return $this->security->getUser();
    }

    /**
     * @param Chambre $chambre
     * @param DateTime $date_arrive
     * @param DateTime $date_depart
     * @return float|null
     */

    public function getTotalreservation(Chambre $chambre, DateTime $date_arrive, DateTime $date_depart):?float
    {
        $nb_jour = $date_arrive->diff($date_depart)->format('%a');
        $tarif_jour = $chambre->getTarif();
        $total = $nb_jour * $tarif_jour;
        return $total;
    }

    public function DonneesUser() {
        $user = $this->getUser();
        $user_nom = $user->getNom();
        $user_prenom = $user->getPrenom();
        $tel = $user->getTel();

        if(!$user_nom || !$user_prenom || !$tel){
                return true; 
          }else{
              return false;
          }
    }

    /**
     * 
     * @param DateTime $date_depart
     * @param DateTime $date_retour
     * @param Integer $id
     * @return Reservation
     */
    
    public function newReservation($date_arrive, $date_depart, $chambre) :Reservation
    {
        $reservation = new Reservation();
        // je set la date d'arrivée
        $reservation->setDateArrive($date_arrive);
        // je set la date de départ
        $reservation->setDateDepart($date_depart);
         // je set le total
        $reservation->setTotal($this->getTotalreservation($this->chambreRepository->find($chambre->getId()), $date_arrive, $date_depart));
        // je récup l'user co
        $user=$this->getUser();
        // je set le user de la reservation
        $reservation->setUser($user);
        $reservation->setNom($user->getNom());
        // je set la chambre de la reservation
        $reservation->setChambre($chambre);

        return $reservation;
    }
}