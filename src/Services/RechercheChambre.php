<?php

namespace App\Services;

use App\Entity\Category;
use DateTime;


class RechercheChambre
{
    /**
     * Date check-in dans la chambre
     * 
     * @var DateTime
     */
    private $date_arrive;

    /**
     *  Date check-out dans la chambre
     * 
     * @var DateTime
     */
    private $date_depart;


    /**
     * @var Category 
     */
    private $category;
    /**
     * Get date check-in dans la chambre
     *
     * @return  DateTime
     */
    public function getDateArrive()
    {
        return $this->date_arrive;
    }

    /**
     *  Set date check-out de la chambre
     * 
     * @param DateTime $date_arrive Date check-in de la chambre
     * 
     * @return self
     * 
     */
    public function setDateArrive(DateTime $date_arrive)
    {
        $this->date_arrive = $date_arrive;

        return $this;
    }

    /**
     * Get date check-out de la chambre
     *
     * @return  DateTime
     */ 
    public function getDateDepart()
    {
        return $this->date_depart;
    }

    /**
     * Set date check-in de la chambre
     *
     * @param DateTime  $date_retour  Date check-out de la voiture
     *
     * @return self
     */ 
    public function setDateDepart(DateTime $date_depart)
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    /**
     * Get the value of category
     *
     * @return  Category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param  Category  $category
     *
     * @return  self
     */ 
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }
}