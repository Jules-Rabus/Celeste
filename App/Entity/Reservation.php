<?php

namespace App\Entity;

use App\Entity\Concert;

class Reservation{

    private int $id;
    private int $id_utilisateur;
    private Concert $concert;
    private int $place_fosse;
    private int $place_or;
    private int $place_argent;
    private int $place_bronze;

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method))
            {
                $this->$method($value);

            }
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    /**
     * @param int $id_utilisateur
     */
    public function setIdUtilisateur(int $id_utilisateur): void
    {
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
     * @return Concert
     */
    public function getConcert(): Concert
    {
        return $this->concert;
    }

    /**
     * @param array $concert
     */
    public function setConcert(array $concert): void
    {
        $this->concert = new Concert($concert);
    }
    
    /**
     * @param int $concert
     */
    public function setConcert(int $id_concert): void
    {
        $ConcertModel = new ConcertModel();

        $this->concert = new Concert($ConcertModel->GetOne($id_concert));
    }

    /**
     * @return int
     */
    public function getPlaceFosse(): int
    {
        return $this->place_fosse;
    }

    /**
     * @param int $place_fosse
     */
    public function setPlaceFosse(int $place_fosse): void
    {
        $this->place_fosse = $place_fosse;
    }

    /**
     * @return int
     */
    public function getPlaceOr(): int
    {
        return $this->place_or;
    }

    /**
     * @param int $place_or
     */
    public function setPlaceOr(int $place_or): void
    {
        $this->place_or = $place_or;
    }

    /**
     * @return int
     */
    public function getPlaceArgent(): int
    {
        return $this->place_argent;
    }

    /**
     * @param int $place_argent
     */
    public function setPlaceArgent(int $place_argent): void
    {
        $this->place_argent = $place_argent;
    }

    /**
     * @return int
     */
    public function getPlaceBronze(): int
    {
        return $this->place_bronze;
    }

    /**
     * @param int $place_bronze
     */
    public function setPlaceBronze(int $place_bronze): void
    {
        $this->place_bronze = $place_bronze;
    }



}