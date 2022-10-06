<?php
namespace App\Controller;

use App\Model\SalleModel;
use App\Entity\Salle;
use App\Entity\Concert;

class ControllerSalle {

    private $controller;

    public function __construct()
    {
        $this->controller = new SalleModel();
    }

    public function GetAll() : void {

        $content = $this->controller->getAll();

        require ('App/View/GetAllSalle.php');
        
    }

    public function Gestion() : void{

        // Traitement pour l'ajout d'une salle

        if( $_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST['salle_nom']) && !empty($_POST['salle_place_or']) && !empty($_POST['salle_place_argent']) && !empty($_POST['salle_place_bronze']) && !empty($_POST['salle_place_fosse']) && strlen($_POST['salle_nom']) < 51){

            $salle_nom = htmlspecialchars($_POST['salle_nom']);
            $salle_place_or = htmlspecialchars($_POST['salle_place_or']);
            $salle_place_argent = htmlspecialchars($_POST['salle_place_argent']);
            $salle_place_bronze = htmlspecialchars($_POST['salle_place_bronze']);
            $salle_place_fosse = htmlspecialchars($_POST['salle_place_fosse']);

            $sql = array("nom"=>$salle_nom,"place_or"=>$salle_place_or,"place_argent"=>$salle_place_argent,"place_bronze"=>$salle_place_bronze,"place_fosse"=>$salle_place_fosse);

            $this->controller->insert($sql);

            // On redirige sur le salle qui vient d'etre ajoute

            $sql = $this->controller->findOne("id",array('1'=>1),"","ORDER BY id DESC LIMIT 1");

            header("Location : #salle_" . $sql['id']);
        }

        // Requete sql et son traitement pour l'affichage des salles

        $salles_sources = $this->controller->getAll();

        foreach ($salles_sources as $cle => $salle){
            $salles[$cle]['salle'] = new Salle($salle);

            foreach($salles[$cle]['salle']->GetConcerts() as $concert){
                $concert = new Concert($concert) ;
                $salles[$cle]['concerts'][$concert->getId()]['concert'] = $concert;
                $salles[$cle]['concerts'][$concert->getId()]['places'] = $concert->getPlaces();
            }

        }
        unset($salles_sources);

        require ('App/View/GetGestionSalle.php');

    }


}

