<?php
namespace App\Controller;

use App\Model\ArtisteModel;
use App\Model\ConcertModel;

class ControllerArtiste
{

    private $controller;

    public function __construct()
    {
        $this->controller = new ArtisteModel();
    }

    public function Gestion() : void{

        // Traitement pour l'ajout d'un artiste

        if( $_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST['nom_artiste']) && !empty($_POST['style_artiste'])){

            // On recupere les informations

            $nom_artiste = htmlspecialchars($_POST['nom_artiste']);
            $style_artiste = htmlspecialchars($_POST['style_artiste']);

            if( strlen($nom_artiste) > 0 && strlen($nom_artiste) < 51){

                if(strlen($style_artiste) > 0 && strlen($style_artiste) < 51){

                    // On fait la requete sql d'insertion

                    $this->controller->insert(array('nom'=>$nom_artiste,'style'=>$style_artiste));
                }
                else {
                    $code_erreur = 1; // Le style de l'artiste est trop long
                }
            }
            else{
                $code_erreur = 2; // Le nom de l'artiste est trop long
            }

        }

        // Traitement pour la suppresion d'un artiste

        if( $_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST['id_artiste']) && isset($_POST['Supprimer'])){

            $id_artiste = htmlspecialchars($_POST['id_artiste']);
            $this->controller->update(array('statut'=>-1),array('id'=>$id_artiste));
        }

        // On recupere les artistes a afficher

        $artistes = $this->controller->findAll("*",array('statut'=>0));

        $date = date('Y-m-d');
        $ConcertModel = new ConcertModel();

        // On traites la requete sql pour l'affichage

        foreach ($artistes as $cle => $artiste){
            $artistes[$cle]['date_futur'] = $ConcertModel->findOne("COUNT(*) as count",array("id_artiste"=>$artiste['id']),""," AND date >= '".$date."'")['count'];
        }

        require ('App/View/GetGestionArtiste.php');
    }

}