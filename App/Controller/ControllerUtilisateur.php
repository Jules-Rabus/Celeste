<?php
namespace App\Controller;

use App\Model\UtilisateurModel;
use App\Model\ConcertModel;
use App\Model\ReservationModel;
use App\Entity\Concert;

class ControllerUtilisateur {

    private $controller;

    public function __construct()
    {
        $this->controller = new UtilisateurModel();
    }

    public function GetCommandes() : void{

        
        if($_SESSION['id_utilisateur']){ // On verifie si l'utilisateur est bien connecte

            // Traitement pour l'annulation d'une reservation

            if( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_reservation']) && isset($_POST['Annuler'])){

                $id_reservation = htmlspecialchars($_POST['id_reservation']);

                $concert_numero = (new ReservationModel())->findOne("id_concert",array("id"=>1))["id_concert"];
                $concert = new Concert((new ConcertModel())->getOne($concert_numero));

                require 'App/Mail/MailAnnulationConcertClient.php';

                $client = $this->controller->findOne("nom,email",array('id'=>$_SESSION['id_utilisateur']));
                mail($client['email'], $sujet, $message_html, $entete);

                (new ReservationModel())->update(array('statut'=>-1),array('id'=>$id_reservation,'id_utilisateur'=>$_SESSION['id_utilisateur']));

                header("Location : compte?compte=commande#formulaire_reservation_annulation_" . $id_reservation);

            }

            // Requete sql et son traitement pour l'affichage

            $date = date("Y-m-d");
            $sql_reservation = $this->controller->FindAll("C.id as concert_id, R.*, U.nom, U.email",array('R.id_utilisateur'=>$_SESSION['id_utilisateur']),"U JOIN reservation R ON U.id = R.id_utilisateur JOIN concert C ON C.id = R.id_concert");

            foreach ($sql_reservation as $commande){
                $commandes[$commande['id']]['concert'] = new Concert((new ConcertModel())->getOne($commande['id_concert']));
                $commandes[$commande['id']]['reservation'] = array('place_or'=>$commande['place_or'],'place_argent'=>$commande['place_argent'],'place_bronze'=>$commande['place_bronze'],'place_fosse'=>$commande['place_fosse'],'statut'=>$commande['statut']);
            }

        }
        else{
            $code_erreur = 1; // l'utilisateur n'est pas connecte
        }
        require ('App/View/GetCommandes.php');
    }

    public function GetFormInformation() : void{

        // Traitement pour la suppression du compte

        if( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_utilisateur']) && isset($_POST['action'])){

            $id_utilisateur = htmlspecialchars($_POST['id_utilisateur']);

            if($_SESSION['id_utilisateur'] == $id_utilisateur){

                $utilisateur = $this->controller->findOne("nom,email",array('id'=>$id_utilisateur));

                require 'App/Mail/MailSuppressionCompte.php';
                mail($utilisateur['email'], $sujet, $message_html, $entete);

                $this->controller->update(array('nom'=>"Supprimer","email"=>"Supprimer"),array('id'=>$id_utilisateur));

                $_SESSION = array();
                header('Location : index');
            }
        }

        // Requete sql et son traitement pour l'affichage

        $utilisateur = $this->controller->findOne("id, nom, email",array('id'=>$_SESSION['id_utilisateur']));

        require ('App/View/GetFormInformation.php');
    }

    public function GetFormMdpChangement() : void{

        // Traiement post pour changement de mdp

        if( $_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST['mdp_actuelle']) && !empty($_POST['mdp_nouveau']) && !empty($_POST['mdp_nouveau_confirmation'])){

            $mdp_actuelle = hash('sha3-512',$_POST['mdp_actuelle']);
            $mdp_nouveau = hash('sha3-512',$_POST['mdp_nouveau']);
            $mdp_nouveau_confirmation = hash('sha3-512',$_POST['mdp_nouveau_confirmation']);
            $mdp_longueur = strlen($_POST['mdp_nouveau']);
            $mdp_conforme = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#',$_POST['mdp_nouveau']);

            if ($mdp_longueur < 31 && $mdp_longueur > 7 && $mdp_conforme ){

                if ($mdp_nouveau === $mdp_nouveau_confirmation){

                    $sql = array("id"=>$_SESSION['id_utilisateur']);
                    $sql=$this->controller->FindOne("nom, email",$sql);

                    if ($sql['email'] != $_POST['mdp_nouveau'] && $sql['nom'] != $_POST['mdp_nouveau']){

                        $sql = array("id"=>$_SESSION['id_utilisateur'],"mdp"=>$mdp_actuelle);
                        $sql=$this->controller->FindOne("COUNT(*) as count",$sql);

                        if($sql['count'] == 1){

                            $sql_data = array("mdp"=>$mdp_nouveau);
                            $sql_find = array("id"=>$_SESSION['id_utilisateur']);

                            if( $this->controller->update($sql_data,$sql_find) ){

                                $utilisateur = $this->controller->findOne("nom, email",array('id'=>$_SESSION['id_utilisateur']));

                                require 'App/Mail/MailMdpChangementConfirmation.php';
                                mail($utilisateur['email'], $sujet, $message_html, $entete);

                                $code_changement = 0; // Changement réussi
                            }
                            else{
                                $code_changement = 1; // problème d'enregistrement
                            }
                        }
                        else{
                            $code_changement = 2; // Mots de passe actuelle : incorrecte
                        }
                    }
                    else{
                        $code_changement = 3; // Nouveau mots de passe : identique au mail/nom
                    }
                }
                else{
                    $code_changement = 4; // Nouveau mots de passe et sa confirmation : différent
                }
            }
            else{
                $code_changement = 5; // Nouveau mots de passe : taille incorrecte
            }
        }
        else{
            $code_changement = 6; // Formulaire incomplet
        }

        require ('App/View/GetFormMdpChangement.php');

    }

    public function GetFormMdpOublie() : void {

        // Reset du numero de formulaire car le changement c'est bien deroule

        if( isset($_SESSION['numform']) && $_SESSION['numform'] == 3){
            $_SESSION = array();
        }

        // Reset du numero de formulaire via le bouton reset, mais on garde les nbre erreur

        if( isset($_POST['reset']) ){
            $nbr_erreur = $_SESSION['nbr_erreur'];
            $_SESSION = array();
            $_SESSION['nbr_erreur'] = $nbr_erreur;
        }

        // On initialise le numero de formulaire quand on arrive sur la page

        if( !isset($_SESSION['numform']) ){
            $_SESSION['numform'] = 0 ;
        }

        // On initialise le tableau des mail quand on arrive sur la page

        if( !isset($_SESSION['mail']) ){
            $_SESSION['mail'] = array();
            $_SESSION['mail']['nombre'] = 0;
            $_SESSION['nbr_erreur'] = 5;
        }

        $erreur = 0;

        if (empty($_SESSION['id_utilisateur'])) {

            if ($_SESSION['nbr_erreur'] > 0) {

                // Numform 0 traitement

                if ( $_SERVER['REQUEST_METHOD'] === "POST" && $_SESSION['numform'] == 0 && !empty($_POST['nom']) && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

                    $_SESSION['mail']['email'] = htmlspecialchars($_POST['email']);
                    $_SESSION['mail']['nom'] = strtoupper(htmlspecialchars($_POST['nom']));

                    $sql = array("email" => $_SESSION['mail']['email'], "nom" => $_SESSION['mail']['nom']);

                    $sql = $this->controller->FindOne("COUNT(*) as count", $sql);

                    if ($sql['count'] == 1) {
                        $_SESSION['numform'] = 1;
                        $_SESSION['mail']['code'] = rand(100000, 999999);                           // Code envoye par mail
                        $_SESSION['mail']['heure'] = strtotime(date('Y-m-d H:i')) + 600;        // Heure de l'envoie du mail
                        $_SESSION['nbr_erreur'] = 5;
                    } else {
                        $erreur = 1;
                        $_SESSION['nbr_erreur']--;
                    }
                }


                // Numform 1 traitement

                if($_SESSION['numform'] == 1){

                    if ( $_SESSION['mail']['heure'] > strtotime(date('Y-m-d H:i'))) {

                        if ($_SESSION['mail']['nombre'] <= 2) {

                            require 'App/Mail/MailMdpOublieCode.php';
                            mail($_SESSION['mail']['email'], $sujet, $message_html, $entete);
                            $_SESSION['mail']['nombre']++;

                        }

                        // Verification du code et du score donner par le recaptacha

                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['recaptcha_response']) && !empty($_POST['code'])) {

                            // Build POST request:
                            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
                            $recaptcha_secret = 'xxxxx'; // clé effacé
                            $recaptcha_response = $_POST['recaptcha_response'];

                            // Make and decode POST request:
                            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
                            $recaptcha = json_decode($recaptcha);

                            // Take action based on the score returned:
                            if ($recaptcha->score >= 0.8) {
                                if ($_SESSION['mail']['code'] == htmlspecialchars($_POST['code'])) {
                                    $_SESSION['numform'] = 2;

                                } else {
                                    $erreur = 1;
                                    $_SESSION['nbr_erreur']--;
                                }
                            } else {
                                $erreur = 2;
                            }
                        }


                    }else {
                        $erreur = 3;
                    }

                }


                // Numform 2 traitement, formulaire pour le changement du mot de passe

                if ( $_SERVER['REQUEST_METHOD'] === "POST" && $_SESSION['numform'] == 2 && !empty($_SESSION['mail']['nom']) && !empty($_SESSION['mail']['email']) && !empty($_POST['mdp1']) && !empty($_POST['mdp2']) ) {

                    $mdp1 = hash('sha3-512', ($_POST['mdp1']));
                    $mdp2 = hash('sha3-512', ($_POST['mdp2']));
                    $mdp_longueur = strlen($_POST['mdp1']);
                    $mdp_conforme = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $_POST['mdp1']);

                    if (!empty($_POST['mdp1']) && !empty($_POST['mdp2'])) {

                        if ($mdp_longueur < 31 && $mdp_longueur > 7 && $mdp_conforme) {

                            if ($mdp1 === $mdp2) {

                                $sql_data = array("mdp" => $mdp1);

                                $sql_find = array("email" => $_SESSION['mail']['email'], "nom" => $_SESSION['mail']['nom']);

                                $sql = $this->controller->Update($sql_data, $sql_find);

                                if ($sql === 1) {
                                    $_SESSION['numform'] = 3;
                                    $_SESSION['mail']['nombre'] = 0;

                                    require 'App/Mail/MailMdpOublieConfirmation.php';
                                    mail($_SESSION['mail']['email'], $sujet, $message_html, $entete);

                                } elseif ($sql === 0) {
                                    $erreur = 1; // Mot de passe identique à l'ancien
                                } else {
                                    $erreur = 2; // Echec enregistrement
                                }
                            } else {
                                $erreur = 3; // Email correspondant au mdp
                            }
                        } else {
                            $erreur = 4; // Mauvaise taille de mdp / pas conforme au pattern
                        }
                    } else {
                        $erreur = 5; // Formulaire incomplet
                    }
                }


            } else {
                $erreur = 7;
            }
        } else {
            $erreur = 8;
        }

        require ('App/View/GetFormMdpOublie.php');

    }

    public function GetFormConnexion() : void
    {

        // Traitement du formulaire de deconnexion

        if (isset($_POST['numform']) && $_POST['numform'] == 3 && isset($_SESSION['id_utilisateur'])) {
            $_SESSION = array();
        }

        // Traitement du formulaire de connexion

        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['numform']) && $_POST['numform'] == 2) {

            $email = htmlspecialchars($_POST['email']);
            $mdp = hash('sha3-512', ($_POST['mdp']));
            $mdp_longueur = strlen($_POST['mdp']);
            $mdp_conforme = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $_POST['mdp']);

            if (!empty($email) && !empty($mdp) && $mdp_longueur > 7 && $mdp_longueur < 31 && filter_var($email, FILTER_VALIDATE_EMAIL) && $mdp_conforme) {

                $sql = array("email" => $email, "mdp" => $mdp);

                $sql = $this->controller->FindOne("COUNT(*) as count, id", $sql);

                if ($sql['count'] == 1) {

                    $_SESSION['id_utilisateur'] = $sql['id'];

                    if (!empty($_SESSION['id_utilisateur'])) {
                        $code_connexion = 0;
                        header("Refresh:0");
                    }
                } else {
                    $code_connexion = 1; // Echec connexion
                }
            } else {
                $code_connexion = 1; // Echec connexion
            }
        }
        require ('App/View/GetFormConnexion.php');
    }

    public function GetFormInscription() : void {

        // Traitement du formulaire d'inscription

        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['numform']) && $_POST['numform'] == 1 ){

            $nom = strtoupper(htmlspecialchars($_POST['nom']));
            $email= htmlspecialchars($_POST['email']);
            $mdp1 = hash('sha3-512',($_POST['mdp1']));
            $mdp2 = hash('sha3-512',($_POST['mdp2']));
            $email_longueur = strlen($email);
            $mdp_longueur = strlen($_POST['mdp1']);
            $mdp_conforme = preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#',$_POST['mdp1']);


            if(!empty($_POST['nom'])  && !empty($_POST['email']) && !empty($_POST['mdp1']) && !empty($_POST['mdp2']) ) {

                if (!isset($_SESSION['id_utilisateur'])) {

                    if (filter_var($email, FILTER_VALIDATE_EMAIL) && $email_longueur < 31 && $email_longueur > 7) {

                        if ($mdp_longueur < 31 && $mdp_longueur > 7 && $mdp_conforme) {

                            if ($mdp1 === $mdp2) {

                                if ($email != $_POST['mdp1'] && $email != $nom) {

                                    $sql = array("email"=>$email);

                                    $sql = $this->controller->FindOne("COUNT(*) as count",$sql);

                                    if ($sql['count'] == 0) {

                                        $sql = array("nom"=>strtoupper($nom),"email"=>$email,"mdp"=>$mdp1);

                                        if ($this->controller->insert($sql)) {

                                            require 'App/Mail/MailConfirmationInscription.php';
                                            mail($email, $sujet, $message_html, $entete);

                                            $code_inscription = 0; // Enregistrement réussi

                                        } else {
                                            $code_inscription = 1; // Echec enregistrement
                                        }
                                    } else {
                                        $code_inscription = 2; // Email deja existant
                                    }
                                } else {
                                    $code_inscription = 3; // Email correspondant au nom / mdp
                                }
                            } else {
                                $code_inscription = 4; // Mdp et mdp confirmation différent, pattern incorrecte
                            }
                        } else {
                            $code_inscription = 5; // Mauvaise taille de mdp
                        }
                    } else {
                        $code_inscription = 6; // Mail invalide, ou mauvaise taille de mail
                    }
                } else {
                    $code_inscription = 7; // Inscription impossible quand connecte
                }
            }else{
                $code_inscription = 8; // Formulaire incomplet
            }
        }
        
        require ('App/View/GetFormInscription.php');
    }


}

