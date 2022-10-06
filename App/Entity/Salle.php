<?php

namespace App\Entity;

use App\Model\ConcertModel;

class Salle{

    private int $id;
    private string $nom;
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
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return int
     */
    public function getPlace_Fosse(): int
    {
        return $this->place_fosse;
    }

    /**
     * @param int $place_fosse
     */
    public function setPlace_Fosse(int $place_fosse): void
    {
        $this->place_fosse = $place_fosse;
    }

    /**
     * @return int
     */
    public function getPlace_Or(): int
    {
        return $this->place_or;
    }

    /**
     * @param int $place_or
     */
    public function setPlace_Or(int $place_or): void
    {
        $this->place_or = $place_or;
    }

    /**
     * @return int
     */
    public function getPlace_Argent(): int
    {
        return $this->place_argent;
    }

    /**
     * @param int $place_argent
     */
    public function setPlace_Argent(int $place_argent): void
    {
        $this->place_argent = $place_argent;
    }

    /**
     * @return int
     */
    public function getPlace_Bronze(): int
    {
        return $this->place_bronze;
    }

    /**
     * @param int $place_bronze
     */
    public function setPlace_Bronze(int $place_bronze): void
    {
        $this->place_bronze = $place_bronze;
    }

    public function getPlaces(): int{
        return $this->place_or + $this->place_argent + $this->place_bronze + $this->place_fosse;
    }

    public function getConcerts() : array{

        $ConcertModel = new ConcertModel();

        $sql = array("id_salle"=>$this->id);

        return $ConcertModel->FindAll("*",$sql,"","ORDER BY date DESC ");

    }

    public function toTab() :array{

        $tab = array();

        foreach ($this as $nom => $valeur){
            if(is_object($valeur)){
                $tab[$nom] = $valeur->toTab();
            }
            else{
                $tab[$nom] = $valeur;
            }

        }
        return $tab;
    }

}