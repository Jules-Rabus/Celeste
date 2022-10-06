<?php
    use App\Config\DataBase;

function verification_page(){ // On verifie si on a les droits d'accès pour cette page

    $connexion = (new DataBase ())->getConnection();

	$url = nom_page(0);

	$page=$connexion->prepare("SELECT admin,connexion FROM page WHERE nom = :nom");
	$page->bindParam(":nom",$url);
	$page->execute();
	$page=$page->fetch(PDO::FETCH_OBJ);

	if ($page->connexion == 1 ){
		
		if( !isset($_SESSION['id_utilisateur'])){
			$_SESSION['verification_page'] = 1;
			require 'header/header.php';
			exit();
			
		}		
		elseif( $page->admin == 1){

			$autorisation=$connexion->prepare("SELECT admin FROM utilisateur WHERE id = :id");
			$autorisation->bindParam(":id",$_SESSION['id_utilisateur']);
			$autorisation->execute();
			$autorisation=$autorisation->fetch(PDO::FETCH_OBJ);

			if ($autorisation->admin == 0){
				$_SESSION['verification_page'] = 2;
				require 'header/header.php';
				exit();
			}
		}

	}

}

function nom_page($redirection){  // Permet d'avoir le nom de la page, ou le lien de la page

    $connexion = (new DataBase ())->getConnection();

    $url = explode(".php",explode("?",$_SERVER['REQUEST_URI'],2)[0])[0];

	$url = str_replace("/","",$url);

	$pages=$connexion->prepare("SELECT nom FROM page WHERE nom != 'accueil'");
	$pages->execute();
	$pages=$pages->fetchAll(PDO::FETCH_OBJ);


	if($url == "" || $url == "index"){
		$page = "Accueil";

		if ($redirection && $page === "Accueil"){
			return "Index";
		}

		return $page;
	}

	foreach($pages as $page){

		if($page->nom == $url){
			return ucfirst($page->nom);
		}

		if($page->nom == $url && $redirection){
			return $page->nom;
		}

	}
}

function page_menu(){ //Permets de faire le menu en utilisant les pages rentrés dans la bdd

    $connexion = (new DataBase ())->getConnection();

	$verif_connexion = 0; 
	$verif_admin = 0;

	if ( isset($_SESSION['id_utilisateur'])){
		$verif_connexion = 1;

		$autorisation=$connexion->prepare("SELECT admin FROM utilisateur WHERE id = :id");
		$autorisation->bindParam(":id",$_SESSION['id_utilisateur']);
		$autorisation->execute();
		$autorisation=$autorisation->fetch(PDO::FETCH_OBJ);

		if($autorisation->admin){
			$verif_admin = 1;
		}

	}

	$pages_lie=$connexion->prepare("SELECT nom, liaison FROM page WHERE connexion <= :connexion AND admin <= :admin AND nom != 'accueil' AND liaison != 0 AND menu != 0 ");
	$pages_lie->bindParam(":connexion",$verif_connexion);
	$pages_lie->bindParam(":admin",$verif_admin);
	$pages_lie->execute();
	$pages_lie=$pages_lie->fetchAll(PDO::FETCH_OBJ);

	$pages=$connexion->prepare("SELECT id, nom, liaison FROM page WHERE connexion <= :connexion AND admin <= :admin AND nom != 'accueil' AND menu != 0");
	$pages->bindParam(":connexion",$verif_connexion);
	$pages->bindParam(":admin",$verif_admin);
	$pages->execute();
	$pages=$pages->fetchAll(PDO::FETCH_OBJ);

	foreach($pages as $page){

		foreach($pages_lie as $page_lie){

			if($page->id == $page_lie->liaison){ // On verifie sur les pages sont liees

				$page_nom = explode('_',$page_lie->nom,2);		// On recupere que le partie apres le _

				if(empty($menu[$page->id])){
					$menu[$page->id] = "<a href='" . $page_lie->nom . "'>" . ucfirst($page_nom[1])  . "</a>";		// On initialise le tableau
				}
				else{
					$menu[$page->id] = $menu[$page->id] . "<a href='" . $page_lie->nom . "'>" . ucfirst($page_nom[1]) . "</a>";	// On rajoute au tableau
				}

			}
		}

		if(!empty($menu[$page->id])){
			$menu[$page->id] = "<a href='" . $page->nom . "' >" . ucfirst($page->nom) . "</a>" . "<div>" . $menu[$page->id] . "</div>";		// On rajoute la div que si le tableau n'est pas vide
		}

		if(empty($menu[$page->id]) && empty($menu[$page->liaison])){
			$menu[$page->id] = "<a href='" . $page->nom . "'>" . ucfirst($page->nom)  . "</a>";		// On rajoute toutes les autres pages non liées
		}

	}

	return $menu;

}

function message_heure(){ // Permet de faire un message personnalisé pour les mails

    date_default_timezone_set('Europe/Paris');
    $soleil = strtotime(date_sunset(time(), SUNFUNCS_RET_STRING, 49.375, 2.1935, 85, 2));
    $heure = strtotime(date('H:i'));
    $jour = date('l');
    $midi = strtotime('12:00:00');
    $aprem = strtotime('16:00:00');
    $soir = strtotime('19:00:00');

    if ($heure > $soleil || $heure > $soir ){
        $debut = 'Bonsoir';
        $fin = 'Bonne soirée';
    }
    else{
        $debut = 'Bonjour';
        $fin = 'Bonne journée';
    }

    if( $heure < $aprem && $heure > $midi  ){
        $fin = 'Bon après midi';
    }
    if( $heure > $aprem && $heure < $soir && $heure < $soleil){
        $fin = 'Bonne fin de journée';
    }
    if ($jour == 'Friday' && ($heure > $soleil && $heure > $soir)){
        $fin = 'Bon week-end';
    }
    if ($jour == 'Sunday' && $heure < $midi){
        $fin = 'Bon dimanche';
    }
    if ($jour == 'Monday' && $heure > $midi && !($heure < $soleil || $heure < $soir)){
        $fin = 'Bonne semaine';
    }

    return array("debut"=>$debut,"fin"=>$fin);
}


?>