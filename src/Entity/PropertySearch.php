<?php

namespace App\Entity;

class PropertySearch
{

    private $Name;

    private $Lastname;

    private $promo;


    /**
     * Get the value of Name
     */
    public function getName(): ?string
    {
        return $this->Name;
    }

    /**
     * Set the value of Name
     *
     * @return  self
     */
    public function setName($Name): PropertySearch
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * Get the value of Lastname
     */
    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    /**
     * Set the value of Lastname
     *
     * @return  self
     */
    public function setLastname($Lastname): PropertySearch
    {
        $this->Lastname = $Lastname;

        return $this;
    }

    /**
     * Get the value of promo
     */
    public function getPromo(): ?string
    {
        return $this->promo;
    }

    /**
     * Set the value of promo
     *
     * @return  self
     */
    public function setPromo($promo): PropertySearch
    {
        $this->promo = $promo;

        return $this;
    }
}
