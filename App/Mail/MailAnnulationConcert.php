<?php
$sujet = "Annulation de votre concert : " . $concert->getNom();
require 'MailHeader.php';
require 'MailFooter.php';

$message_html = $header . "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $client['nom'] . ",<br><br>
        Votre réservation du concert de " . $concert->getArtiste()->getNom() . " du " . $concert->getDate() . " est annulé.<br>
        Raison : " . $raison . ". Un remboursement vous sera envoyé dans un délais de 10 jours.
        </p>

    </article>" . $footer;

