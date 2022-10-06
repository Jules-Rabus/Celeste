<?php
$sujet = "Décalage de votre concert : " .$concert->getNom();
require 'MailHeader.php';
require 'MailFooter.php';

$message_html = $header . "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $client['nom'] . ",<br><br>
        Votre réservation du concert de " . $concert->getArtiste()->getNom() . " du " . $concert->getDate() . " est décalé au " . $date_decalage . "<br>
        Raison : " . $raison . " Rendez vous sur votre espace client si vous souhaitez annuler votre réservation.
        </p>

    </article>" . $footer;

