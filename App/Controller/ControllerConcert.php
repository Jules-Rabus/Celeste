<?php
namespace App\Controller;

use App\Model\ConcertModel;
use App\Model\ReservationModel;
use App\Model\UtilisateurModel;
use App\Model\ArtisteModel;
use App\Model\SalleModel;
use App\Entity\Concert;


class ControllerConcert {

    private $controller;

    public function __construct()
    {
        $this->controller = new ConcertModel();
    }

    public function GetAll() : void {

        $content = $this->controller->GetAll();

        require ('App/View/GetAllConcert.php');

    }

    public function GetAllConcert() : void{

        // On recupere les concerts

        $date = date('Y-m-d');

        $concerts = $this->controller->findAll("*",array('statut'=>0),""," AND date >= '".$date."' ORDER BY date");

        // On traite la requete sql pour l'affichage

        foreach ($concerts as $cle => $concert){
            $concerts[$cle] = new Concert($concert);
        }

        require ('App/View/GetAllConcert.php');
    }

    public function GetOne($id) : void{

        $content = $this->controller->GetOne($id);

        require ('App/View/GetAllConcert.php');
    }

    public function GetFormReservation() : void
    {

        // Traitement pour l'affichage du formulaire d'une reservation

        if (isset($_GET['id_concert'])) {   // On vérifie que l'url comporte bien l'id d'un concert

            $id_concert = htmlspecialchars($_GET['id_concert']);

            $sql = array("id" => $id_concert);
            $sql = $this->controller->FindOne("COUNT(*) as count", $sql);

            $erreur = 0;

            if ($sql['count'] == 1) {        // On verifie si le concert existe

                if (isset($_SESSION['id_concert']) && $id_concert != $_SESSION['id_concert']) {
                    unset($_SESSION['id_concert'], $_SESSION['prix_concert'], $_SESSION['places']);
                }

                $concert = $this->controller->GetOne($id_concert);
                $concert = new Concert($concert);

                if ($concert->getStatut() == 0) { // On verifie que le concert ne soit pas annule

                    if ($concert->getDate() > date("Y-m-d")) { // On verifie si la date est superieur a celle du jour

                        //Traitement pour l'affichage des prix en verifiant la disponibilite

                        $places = $concert->getPlaces();

                        if (isset($_GET['places'], $_GET['places']['place_or'], $_GET['places']['place_argent'], $_GET['places']['place_bronze'], $_GET['places']['place_fosse']) && count($_GET['places']) === 4) { // isset car empty pense que vide si = 0

                            foreach ($_GET['places'] as $nom_place => $place) {
                                if ( $place >= 0 && $places[$nom_place . "_reservable"] >= $place) {
                                    $_SESSION['places'][$nom_place] = $place;
                                }
                            }

                            $_SESSION['id_concert'] = $id_concert;
                        }


                        // Traitement pour l'ajout dans le panier

                        if ( $_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['ajouter'], $_SESSION['id_concert'], $_SESSION['places']) && $_GET['id_concert'] == $_SESSION['id_concert']) {

                            $_SESSION['panier'][$_SESSION['id_concert']]['places'] = $_SESSION['places'];
                            unset($_SESSION['reservation_possible'],$_SESSION['places'],$_SESSION['id_concert']);
                            header('Refresh:0');

                            //On refresh pour que le panier s'affichage dans le menu
                        }

                        if( isset($_SESSION['places'])){

                            $reservation_vide = 1;

                            foreach($_SESSION['places'] as $place){
                                if($place > 0){
                                    $reservation_vide = 0;
                                    break;
                                }
                            }

                            $prix = $concert->prix($_SESSION['places']);
                        }
                        else{
                            $reservation_vide = 0;
                            $prix = $concert->prix(array());
                        }


                    } else {
                        $erreur = 1; // Le concert est passé
                    }
                } else {
                    $erreur = 2; // Le concert est annulé
                }
            } else {
                $erreur = 3; // Le concert est inexistant
            }

            require('App/View/GetFormReservation.php');
        }

    }

    public function Gestion() : void{

        if(!empty($_POST['id_concert'])){

            $id_concert = htmlspecialchars($_POST['id_concert']);

            $concert = $this->controller->getOne($id_concert);
            $concert = new Concert($concert);

            $UtilisateurModel = new UtilisateurModel();
            $mail = $UtilisateurModel->findAll("nom, email",array("R.id_concert"=>$id_concert,"statut"=> 0),"U JOIN reservation R ON R.id_utilisateur = U.id");

            // traitement pour une annulation

            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['annuler']) && !empty($_POST['raison']) ){

                $raison = htmlspecialchars($_POST['raison']);

                foreach($mail as $client){
                    require 'App/Mail/MailAnnulationConcert.php';
                    mail($client['email'], $sujet, $message_html, $entete);
                }

                (new ReservationModel())->update(array("statut"=>-1),array("id_concert"=>$id_concert));
                $this->controller->update(array("statut"=>-1),array("id"=>$id_concert));

                header("Location : gestion?gestion=concert#formulaire_gestion_concert_" . $id_concert);     // On redirige au formulaire du concert

            }
            elseif( $_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['decaler']) && !empty($_POST['date_decalage']) && !empty($_POST['raison'])){        // traitement pour un décalage

                $raison = htmlspecialchars($_POST['raison']);
                $date_decalage = htmlspecialchars($_POST['date_decalage']);

                foreach($mail as $client){
                    require 'App/Mail/MailDecalageConcert.php';
                    mail($client['email'], $sujet, $message_html, $entete);
                }

                $this->controller->update(array("date"=>$date_decalage),array("id"=>$id_concert));

                header("Location : gestion?gestion=concert#formulaire_gestion_concert_" . $id_concert);     // On redirige au formulaire du concert

            }
            else{
                header("Location : gestion?gestion=concert#formulaire_gestion_concert_" . $id_concert);     // Sinon on redirige au formulaire du concert
            }

        }

        // traitement de l'ajout d'un concert

        if(!empty($_POST['concert_nom']) && !empty($_POST['concert_date']) && !empty($_POST['concert_artiste']) && !empty($_POST['concert_salle'])){

            $concert_nom = htmlspecialchars($_POST['concert_nom']);
            $concert_date = htmlspecialchars($_POST['concert_date']);
            $concert_id_artiste = htmlspecialchars($_POST['concert_artiste']);
            $concert_id_salle= htmlspecialchars($_POST['concert_salle']);

            $verif_artiste = (new ArtisteModel())->findOne("COUNT(*) as count",array("id"=>$concert_id_artiste));
            $verif_salle = (new SalleModel())->findOne("COUNT(*) as count",array("id"=>$concert_id_salle));
            $verif_salle_dispo = $this->controller->findOne("COUNT(*) as count",array("id_salle"=>$concert_id_salle,"date"=>$concert_date));

            $date_concert_ajout = date("Y-m-d",strtotime(date('Y-m-d'))+86400*15);

            if($concert_date >= $date_concert_ajout && strlen($_POST['concert_nom']) < 51 && $verif_salle['count'] && $verif_salle_dispo['count'] == 0 && $verif_artiste['count'] == 1 && $_FILES['concert_image']['size'] < 10000000 &&  $_FILES['concert_image']['error'] == 0 ){

                $enregistrement=move_uploaded_file($_FILES["concert_image"]["tmp_name"],"images/".$_FILES["concert_image"]["name"]);
                $chemin_image = $_FILES["concert_image"]["name"];

                if($enregistrement){

                    $this->controller->insert(array("id_salle"=>$concert_id_salle,"id_artiste"=>$concert_id_artiste,"nom"=>$concert_nom,"date"=>$concert_date,"image"=>$chemin_image));

                    $artiste = (new ArtisteModel())->getOne($concert_id_artiste);
                    $UtilisateurModel = new UtilisateurModel();
                    $utilisateurs = $UtilisateurModel->findAll("nom ,email",array(),""," email != 'Supprimer'");

                    foreach ($utilisateurs as $utilisateur){
                        require 'App/Mail/MailNouveauConcert.php';
                        mail($utilisateur['email'], $sujet, $message_html, $entete);
                    }

                    $sql = $this->controller->findOne("id",array('1'=>1),"","ORDER BY id DESC LIMIT 1");

                    header("Location : gestion?gestion=concert#formulaire_gestion_concert_" . $sql['id']);
                }


            }
            else{
                $erreur = 1; // Il y a un problème avec le concert
            }
        }

        // traitement pour l'affichage des changements d'un concert

        if(!empty($_GET['id_concert'])){

            $id_concert = htmlspecialchars($_GET['id_concert']);
            $sql = $this->controller->findOne("COUNT(*) as count",array("id"=>$id_concert,"statut"=>0));

            if($sql['count']) {

                $concert['concert'] = new Concert($this->controller->getOne($id_concert));
                $concert['places'] = $concert['concert']->getPlaces();
                $impact = (new ReservationModel())->findOne("COUNT(*) as mail, COUNT(DISTINCT(id_utilisateur)) as client",array("id_concert"=>$id_concert));

                // traitement pour l'affichage lors d'un décalage

                if (isset($_GET['decaler'])) {
                    $nbr_jours_decalage_max = 8;
                    $date_debut_decalage = date("Y-m-d", strtotime($concert['concert']->getDate()) + 86400);
                    $date_fin_decalage = date("Y-m-d", strtotime($concert['concert']->getdate()) + 86400 * $nbr_jours_decalage_max);

                    $salle_dispo = $this->controller->findAll("date",array("id_salle"=>$concert['concert']->getSalle()->getId()),"","AND date > '".$date_debut_decalage."' AND date <= '".$date_fin_decalage ."'");

                    for ($i = 1; $i < $nbr_jours_decalage_max; $i++) {

                        $dispo = 1;
                        $date_test = date("Y-m-d", strtotime($concert['concert']->getDate()) + 86400 * $i);

                        foreach ($salle_dispo as $date) {
                            if ($date['date'] == $date_test) {
                                $dispo = 0;
                                break;
                            }
                        }
                        if ($dispo) {
                            $date_decalage_possible[$i] = date("Y-m-d", strtotime($concert['concert']->getDate()) + 86400 * $i);
                        }
                    }
                }
            }
            else{
                $concert_annule = $this->controller->findOne("COUNT(*) as count",array("id"=>$id_concert,"statut"=>-1))['count'];
            }
        }else{                                                  // traitement pour l'affichage des concerts et l'ajout d'un concert
            $artistes = (new ArtisteModel())->findAll("*",array('statut'=>0));
            $salles = (new SalleModel())->getAll();

            $date_concert_ajout = date("Y-m-d",strtotime(date('Y-m-d'))+86400*15);

            $ControllerConcert = new ControllerConcert();
            $concerts = (new ConcertModel())->GetAll();

            foreach ($concerts as $cle => $concert){
                $concert = new Concert($concert);
                $concerts[$cle] = array('concert'=>$concert,'places'=>$concert->getPlaces());
            }

        }
        require ('App/View/GetGestionConcert.php');
    }

}

