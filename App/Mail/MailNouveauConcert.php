<?php
$sujet = "Nouveau concert de : " .$artiste['nom'];
require 'MailHeader.php';
require 'MailFooter.php';

$message_html = $header . "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $utilisateur['nom'] . ",<br><br>
        Un nouveau concert de " . $artiste['nom'] . " aura lieu le : " . $concert_date . "<br>
        </p>

    </article>" . $footer;

