<?php
    $sujet = "Confirmation de la récupération de votre compte";
    require 'MailHeader.php';
    require 'MailFooter.php';

    $message_html = "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $_SESSION['mail']['nom'] . ", <br><br>
            Votre mot de passe a bien été changé. <br><br>
            Si vous n'êtes pas à l'origine de cette demande veuillez nous contacter. <br><br>
        </p>

    </article>"  . $footer;