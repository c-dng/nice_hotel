<?php

namespace App\Services;

use App\Repository\ReservationRepository;
use App\Repository\ChambreRepository;
use DateTime;

class SelectChambreDispo

{
    private $chambreRepository;
    private $reservationRepository;

    public function __construct(ChambreRepository $chambreRepository, ReservationRepository $reservationRepository)
    {
        $this->chambreRepository = $chambreRepository;
        $this->reservationRepository = $reservationRepository;
    }

/**
 * récup les réservations entrant dans les dates et renvoie un tableau d'ID des chambres non disponibles pour les dates
 * 
 * @param DateTime $date_arrivee
 * @param DateTime $date_retour
 * @return array
 */

    // je récupère les chambres louées par rapport à la date passée
    // je récupère le tableau de toutes les chambres
    public function getChambresResa($date_arrive, $date_depart, $category)
    {
    // je récupère les chambres réservées des dates du formulaire
    $tab_id = [];
    $liste_chambre_resa = $this->reservationRepository->findChambresResa($date_arrive, $date_depart, $category);
    
    // je récup les id des chambres réservées
    foreach ($liste_chambre_resa as $resa_chambre) {
        $tab_id[] = $resa_chambre->getChambre()->getId();
    }

    // tableau des chambres indispo
    return $tab_id;
    }

    public function getChambresDispo($date_arrive, $date_depart, $category)
    {
        // je récupère le tableau des chambres non disponibles
        $tab_id_chambre_Ndispo = $this->getChambresResa($date_arrive, $date_depart, $category);
        // je récupère les chambres du site de la catégorie sélectionnée
        $tab_chambres = $this->chambreRepository->findBy(['category' => $category]);
        // je déclare un tableau vide
        $tab_chambre_dispo = [];
        // je parcours les chambres
        foreach ($tab_chambres as $chambre) {
            // si l'id de la chambre n'est pas dans le tableau des chambres réservées, je la rajoute au tableau des chambres dispo
            if (!in_array($chambre->getId(), $tab_id_chambre_Ndispo)) {
                $tab_chambre_dispo[] = $chambre;
            }
        }
        
        return $tab_chambre_dispo;
    }
}