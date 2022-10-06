<?php
$sujet = "Confirmation de votre réservation " . $concert->getNom();
require 'MailHeader.php';
require 'MailFooter.php';

$message_html = $header . "
    <article class='message'>
        <h1>" . $sujet . "</h1>

        <p>" . message_heure()['debut'] . ", Madame / Monsieur " . $utilisateur['nom'] . ",<br><br>
            Nous vous confirmons votre réservation de votre concert.
        </p>

        <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Date</th>
            <th>Salle</th>
            <th>Place or</th>
            <th>Place argent</th>
            <th>Place bronze</th>
            <th>Place fosse</th>
        </tr>
        </thead>
            <tr>
                <td>" . $concert->getNom() . "</td>
                <td>" . $concert->getDate() . "</td>
                <td>" . $concert->getSalle()->getNom() . "</td>
                <td>" . $valeur['places']['place_or'] . "</td>
                <td>" . $valeur['places']['place_argent'] . "</td>
                <td>" . $valeur['places']['place_bronze'] . "</td>
                <td>" . $valeur['places']['place_fosse'] . "</td>
            </tr>
        <tbody>
        </tbody>
        </table>
        
        <h3>Prix : " . $concert->prix($valeur['places'])['total'] . " €</h3>


            
    </article>" . $footer;

