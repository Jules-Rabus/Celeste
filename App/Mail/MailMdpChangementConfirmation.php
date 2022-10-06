<?php
$sujet = "Confirmation du changement de mot de passe";
require 'MailHeader.php';
require 'MailFooter.php';

$message_html = "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $utilisateur['nom'] . ", <br><br>
            Votre mot de passe a bien été changé. <br><br>
            Si vous n'êtes pas à l'origine de cette demande veuillez nous contacter. <br><br>
        </p>

    </article>"  . $footer;