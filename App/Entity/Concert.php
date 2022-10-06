<?php

namespace App\Entity;

use App\Entity\Salle;
use App\Entity\Artiste;
use App\Model\ArtisteModel;
use App\Model\SalleModel;
use App\Model\ReservationModel;
//use App\Config\DataBase;


class Concert{

    private int $id;
    private Salle $salle;
    private Artiste $artiste;
    private string $nom;
    private string $date;
    private string $image;
    private int $statut;

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
     * @return Salle
     */
    public function getSalle(): Salle
    {
        return $this->salle;
    }

    /**
     * @param int $id_salle
     */
    public function setId_salle(int $id_salle): void
    {
        $SalleModel = new SalleModel();
        $this->salle = new Salle ($SalleModel->GetOne($id_salle));
    }

    /**
     * @param array $salle
     */
    public function setSalle(array $salle): void
    {
        $this->salle = new Salle ($salle);
    }

    /**
     * @return Artiste
     */
    public function getArtiste(): Artiste
    {
        return $this->artiste;
    }

    /**
     * @param int $id_artiste
     */
    public function setId_artiste(int $id_artiste): void
    {
        $ArtisteModel = new ArtisteModel();
        $this->artiste = new Artiste ($ArtisteModel->GetOne($id_artiste));
    }

    /**
     * @param array $artiste
     */
    public function setArtiste(array $artiste): void
    {
        $this->artiste = new Artiste ($artiste);
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
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getStatut(): int
    {
        return $this->statut;
    }

    /**
     * @param int $statut
     */
    public function setStatut(int $statut): void
    {
        $this->statut = $statut;
    }

    public function getPlaces()
    {

        $salle = array ("place_or_reserve"=>0,"place_argent_reserve"=>0,"place_bronze_reserve"=>0,"place_fosse_reserve"=>0);

        $sql = array("id_concert"=>$this->id);

        $places = (new ReservationModel())->findAll("*",$sql);

        foreach ($places as $place) {
            $salle['place_fosse_reserve'] += $place['place_fosse'];
            $salle['place_or_reserve'] += $place['place_or'];
            $salle['place_argent_reserve'] += $place['place_argent'];
            $salle['place_bronze_reserve'] += $place['place_bronze'];
        }

        foreach ($salle as $cle => $place ){
            $cle = substr($cle,0,strlen($cle)-8);
            $method = 'get'.$cle;
            $salle[$cle] = $this->getSalle()->$method();

            if( $salle[$cle] - $place > 10  ){
                $salle[$cle."_reservable"] = 10;
            }
            else{
                $salle[$cle."_reservable"] = $salle[$cle] - $place;
            }
        }
        $salle['prix'] = 0;

        if($salle['place_fosse_reservable'] > 0){
            $salle['prix'] = 35;
        }
        elseif($salle['place_bronze_reservable'] > 0){
            $salle['prix'] = 45;
        }
        elseif($salle['place_argent_reservable'] > 0){
            $salle['prix'] = 55;
        }
        elseif($salle['place_or_reservable'] > 0){
            $salle['prix'] = 70;
        }

        $salle += array("places" => $this->getSalle()->getPlaces(),"places_reserve"=> $salle['place_or_reserve'] +
            $salle['place_argent_reserve'] + $salle['place_bronze_reserve'] + $salle['place_fosse_reserve']);

        $salle['perte'] = $salle['place_fosse_reserve'] * 35 + $salle['place_or_reserve'] * 70 + $salle['place_argent_reserve'] * 55 + $salle['place_bronze_reserve'] * 45;

        return $salle;

    }

    public function prix($places) : array{

        $prix['place_or'] = 0;
        $prix['place_argent'] = 0;
        $prix['place_bronze'] = 0;
        $prix['place_fosse'] = 0;

        foreach( $places as $nom_place => $place){

            switch ($nom_place) {
                case "place_or":
                    $prix['place_or'] += $place * 70;
                    break;

                case "place_argent":
                    $prix['place_argent'] += $place * 55;
                    break;

                case "place_bronze":
                    $prix['place_bronze'] += $place * 45;
                    break;

                case "place_fosse":
                    $prix['place_fosse'] += $place * 35;
                    break;
            }

        }
        $prix['total'] = $prix['place_or'] + $prix['place_argent'] + $prix['place_bronze'] + $prix['place_fosse'];
        return $prix;
    }

    public function toTab() : array{

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