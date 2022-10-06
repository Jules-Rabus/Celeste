<?php
namespace App\Controller;

use App\Model\ReservationModel;
use App\Model\UtilisateurModel;
use App\Model\ConcertModel;
use App\Entity\Concert;

class ControllerReservation {

    private $controller;

    public function __construct()
    {
        $this->controller = new ReservationModel();
    }

    public function Gestion() : void{

        // Traitement pour l'annulation d'une reservation

        if( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_reservation']) && !empty($_POST['raison']) && isset($_POST['Annuler'])){

            $id_reservation = htmlspecialchars($_POST['id_reservation']);
            $raison = htmlspecialchars($_POST['raison']);

            $reservation = $this->controller->getOne($id_reservation);
            $concert = new Concert( (new ConcertModel())->getOne($reservation['id_concert']));
            $client = (new UtilisateurModel())->FindOne("nom, email",array("id"=>$reservation['id_utilisateur']));

            require 'App/Mail/MailAnnulationConcert.php';
            mail($client['email'], $sujet, $message_html, $entete);

            $this->controller->update(array("statut"=>-1),array("id"=>$id_reservation));

        }

        // Traitement pour la recherche d'une reservation

        if(isset($_GET['recherche_type']) && !empty($_GET['recherche'])){

            $recherche_type = htmlspecialchars($_GET['recherche_type']);
            $recherche = htmlspecialchars($_GET['recherche']);
            $reservations_recherche = array();

            // Switch pour faire la bonne recherche sql en fonction du type de recherche

            switch ($recherche_type){
                case "Nom":
                    $noms = (new UtilisateurModel())->findAll("id",array("nom"=>strtoupper($recherche)));
                    foreach ($noms as $nom){
                        $client_reservation = $this->controller->findAll("*",array("id_utilisateur"=>$nom['id']));
                        foreach ($client_reservation as $reservation){
                            $reservations_recherche[$reservation['id']] = $reservation;
                        }
                    }
                    break;

                case "Email":
                    $emails = (new UtilisateurModel())->findAll("id",array("email"=>$recherche));

                    foreach ($emails as $email){
                        $client_reservation = $this->controller->findAll("*",array("id_utilisateur"=>$email['id']));
                        foreach ($client_reservation as $reservation){
                            $reservations_recherche[$reservation['id']] = $reservation;
                        }
                    }
                    break;

                case "Id_Concert":
                    $reservations_recherche = $this->controller->findAll("*",array("id_concert"=>$recherche));
                    break;

                case "Numéro de réservation":
                    $reservations_recherche  = $this->controller->findAll("*",array("id"=>$recherche));
                    break;

                case "Date":
                    $dates = (new ConcertModel())->findAll("id",array("date"=>$recherche));

                    foreach ($dates as $date){
                        $reservations_recherche += $this->controller->findAll("*",array("id_concert"=>$date['id']));
                    }
                    break;

            }

        }
        else{
            $reservations_recherche = $this->controller->getAll();
        }

        // Traiment pour l'affichage

        foreach ($reservations_recherche as $cle => $reservation){
            $reservations[$cle]['reservation'] = $reservation;
            $reservations[$cle]['concert'] = new Concert( (new ConcertModel())->getOne($reservation['id_concert']));
            $reservations[$cle]['utilisitateur'] = (new UtilisateurModel())->findOne("nom, email",array("id"=>$reservation['id_utilisateur']));
        }

        require ('App/View/GetGestionReservation.php');
    }


}

