<?php
    $sujet = "Code de récupération de votre compte";
    require 'MailHeader.php';
    require 'MailFooter.php';

    $message_html = $header . "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $_SESSION['mail']['nom'] . ", <br><br>
            Nous avons reçu une demande de récupération de votre compte. <br><br>
            Le code de récupération est le : " . $_SESSION['mail']['code'] . " <br><br>
            Si vous n'êtes pas à l'origine de cette demande veuillez nous contacter <br><br>
        </p>

    </article>" . $footer;

