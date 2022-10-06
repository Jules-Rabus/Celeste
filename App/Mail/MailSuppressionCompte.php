<?php
$sujet = "Suppresion de votre compte Celeste";
require 'MailHeader.php';
require 'MailFooter.php';

$message_html = $header . "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $utilisateur['nom'] . ",<br><br>
        Votre compte a bien été supprimé de notre site.
        </p>

    </article>" . $footer;

