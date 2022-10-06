<?php

    function chargerClasse($classe)
    {
        $classe=str_replace('\\','/',$classe);
        require $classe . '.php';
    }

    spl_autoload_register('chargerClasse'); //Autoload

    session_start();
    require_once 'fonction.php';
    verification_page();

    use App\Controller\ControllerUtilisateur;
    use App\Model\ConcertModel;
    use App\Model\ReservationModel;
    use App\Model\UtilisateurModel;
    use App\Entity\Concert;

    $ControllerUtilisateur = new ControllerUtilisateur();
    $ConcertModel = new ConcertModel();
    $ReservationModel = new ReservationModel();
    $UtilisateurModel = new UtilisateurModel();

    // Traitement du formulaire pour vider le panier

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vider'])){
        unset($_SESSION['panier']);
    }

    // Traitement du formulaire pour changer les quantites du panier

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['places'], $_POST['places']['place_or'], $_POST['places']['place_argent'], $_POST['places']['place_bronze'], $_POST['places']['place_fosse'])
        && count($_POST['places']) === 4 && !empty($_POST['id_concert'])) {

        $id_concert = htmlspecialchars($_POST['id_concert']);

        if(isset($_SESSION['panier'][$id_concert])){

            $places_post = $_POST['places'];
            $places_post_vide = 0;

            foreach ($places_post as $valeur){
                if(!empty($valeur)){
                    $places_post_vide = 1;
                }
            }

            if($places_post_vide){
                $concert = new Concert($ConcertModel->getOne($id_concert));
                $places = $concert->getPlaces();

                foreach($places_post as $cle => $place){
                    if($place >= 0 && $place <= $places[$cle . "_reservable"]){
                        $_SESSION['panier'][$id_concert]['places'][$cle] = $place;
                    }
                }

            }
            else{
                unset($_SESSION['panier'][$id_concert]);
            }

        }
    }

    // Traitement du formulaire pour supprimer un concert du panier

    if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_concert']) && isset($_POST['supprimer']) ){

        $id_concert = htmlspecialchars($_POST['id_concert']);

        if(isset($_SESSION['panier'][$id_concert])){
            unset($_SESSION['panier'][$id_concert]);
        }

    }

    // Traitement du formulaire de paiement

    if(!empty($_SESSION['panier'] )){

        $ConcertModel = new ConcertModel();
        $prix_total = 0;

        foreach ($_SESSION['panier'] as $id_concert => $valeur){
            $prix_total += (new Concert($ConcertModel->getOne($id_concert)))->prix($valeur['places'])['total'];
        }

        if( isset($_SESSION['id_utilisateur'])) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['stripeToken'])) {

                require_once 'App/Stripe/init.php';

                $email = "xxxx@gmail.com"; // email effacé
                $token = $_POST['stripeToken'];

                $stripe = new \Stripe\StripeClient("xxxx"); // clé efface

                try {
                    $customer = $stripe->customers->create(array(
                        'email' => $email,
                        'source' => $token
                    ));

                    $intent = $stripe->paymentIntents->create([
                        'amount' => $prix_total * 100,
                        'currency' => 'eur',
                        'payment_method' => "pm_card_visa",
                        'confirm' => true,
                    ]);

                    if ($intent->status === "succeeded") {
                        $paiement_reussi = true;
                    }

                } catch (\Stripe\Exception\CardException $e) {
                    $paiement_reussi = false;
                    $paiement_message = $e->getError()->message;
                } catch (\Stripe\Exception\RateLimitException $e) {
                    $paiement_reussi = false;
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    $paiement_reussi = false;
                } catch (\Stripe\Exception\AuthenticationException $e) {
                    $paiement_reussi = false;
                } catch (\Stripe\Exception\ApiConnectionException $e) {
                    $paiement_reussi = false;
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    $paiement_reussi = false;
                } catch (Exception $e) {
                    $paiement_reussi = false;
                }

                if ($paiement_reussi) {

                    $utilisateur = $UtilisateurModel->getOne($_SESSION['id_utilisateur']);

                    foreach ($_SESSION['panier'] as $id_concert => $valeur) {

                        $sql = array("id_utilisateur" => $_SESSION['id_utilisateur'], "id_concert" => $id_concert, "place_or" => $valeur['places']['place_or'],
                            "place_argent" => $valeur['places']['place_argent'],"place_bronze"=> $valeur['places']['place_bronze'], "place_fosse" => $valeur['places']['place_fosse']);

                        $ReservationModel->insert($sql);

                        $concert = $ConcertModel->getOne($id_concert);
                        $concert = new Concert($concert);

                        require 'App/Mail/MailReservationConfirmation.php';
                        mail($utilisateur['email'], $sujet, $message_html, $entete);

                    }

                    unset($_SESSION['panier']);
                }
            }
        }
    }

    require 'header/header.php';

?>

    <div class='flex'>

        <?php if(isset($paiement_reussi)) : ?>
            <div>
                <?php if(!$paiement_reussi) : ?>

                    <?php if(isset($paiement_message)) : ?>
                        <h2>Problème de paiement : <?=$paiement_message?></h2>
                    <?php else : ?>
                        <h2>Problème de paiement</h2>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if($paiement_reussi) : ?>
                    <h2>Paiement réussi</h2>
                    <h3>Un mail de confirmation vous a été envoyé</h3>
                <?php endif; ?>
            </div>

        <?php endif; ?>


        <?php if(!empty($_SESSION['panier'])) : ?>

            <?php if( isset($paiement_reussi) && !$paiement_reussi || !isset($paiement_reussi) ) : ?>

                <?php foreach($_SESSION['panier'] as $id_concert => $valeur) :
                    $concert = new Concert($ConcertModel->GetOne($id_concert));
                    $places = $concert->getPlaces();
                    $prix = $concert->prix($valeur['places']);
                    ?>

                    <table class='panier'>
                    <thead>
                    <tr>
                        <th>Concert</th>
                        <th>Date</th>
                        <th>Quantité Or</th>
                        <th>Quantité Argent</th>
                        <th>Quantité Bronze</th>
                        <th>Quantité Fosse</th>
                        <th>Action</th>
                        <th>Prix Concert</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?=$concert->getNom()?>
                        </td>
                        <td>
                            <?=$concert->getDate()?>
                        </td>
                        <form id='formulaire_panier_quantite_<?=$id_concert?>' method='post'>

                            <td>
                                <select name="places[place_or]" size="1">
                                    <?php for( $i = 0; $i< $places['place_or_reservable'] +1; $i++) : ?>

                                        <?php if($i == $valeur['places']['place_or'] ) : ?>
                                            <option selected='selected'><?=$i?></option>
                                        <?php else : ?>
                                            <option><?=$i?></option>
                                        <?php endif ;?>

                                    <?php endfor; ?>
                                </select>
                            </td>
                            <td>
                                <select name="places[place_argent]" size="1">
                                    <?php for( $i = 0; $i< $places['place_argent_reservable'] +1; $i++) : ?>

                                        <?php if($i == $valeur['places']['place_argent'] ) : ?>
                                            <option selected='selected'><?=$i?></option>
                                        <?php else : ?>
                                            <option><?=$i?></option>
                                        <?php endif ;?>

                                    <?php endfor; ?>
                                </select>
                            </td>

                            <td>
                                <select name="places[place_bronze]" size="1">
                                    <?php for( $i = 0; $i< $places['place_bronze_reservable'] +1; $i++) : ?>

                                        <?php if($i == $valeur['places']['place_bronze'] ) : ?>
                                            <option selected='selected'><?=$i?></option>
                                        <?php else : ?>
                                            <option><?=$i?></option>
                                        <?php endif ;?>

                                    <?php endfor; ?>
                                </select>
                            </td>

                            <td>
                                <select name="places[place_fosse]" size="1">
                                    <?php for( $i = 0; $i< $places['place_fosse_reservable'] +1; $i++) : ?>

                                        <?php if($i == $valeur['places']['place_fosse'] ) : ?>
                                            <option selected='selected'><?=$i?></option>
                                        <?php else : ?>
                                            <option><?=$i?></option>
                                        <?php endif ;?>

                                    <?php endfor; ?>
                                </select>
                                <input name='id_concert' type='number' value='<?=$id_concert?>' hidden required>
                            </td>
                        </form>

                        <td>
                            <button type='submit' form='formulaire_panier_quantite_<?=$id_concert?>'>
                                Changer quantité
                            </button>

                            <form id='formulaire_panier_supprimer_<?=$id_concert?>' method='post'>
                                <input name='id_concert' type='number' value='<?=$id_concert?>' hidden required>
                                <p class='submit'>
                                    <input type='submit' name='supprimer' value='Supprimer'/>
                                </p>
                            </form>
                        </td>
                        <td>
                            <?=$prix['total']?> €
                        </td>
                    </tr>
                    </tbody>

                <?php endforeach; ?>

                <tfoot>
                <tr>
                    <td colspan="7">
                        <?php if(isset($_SESSION['id_utilisateur'])) : ?>
                            <form id='panier_paiement' method='POST'>
                                <script
                                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                        data-key="xxxxx"
                                        data-amount="(<?=$prix_total?>)*100"
                                        data-local ="auto"
                                        data-currency="eur"
                                        data-label="Procéder au paiement">
                                </script>
                            </form>

                        <?php else : ?>
                            <?=$ControllerUtilisateur->getFormConnexion()?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <h3>Le prix total est de <?=$prix_total?> €</h3>
                    </td>
                </tr>
                </tfoot>

                </table>

                <form id='panier_vider' method='post'>
                    <p class='submit'>
                        <input type='submit' name='vider' value='Vider panier'>
                    </p>
                </form>

            <?php endif; ?>

        <?php else : ?>
            <div>
                <h2>Votre panier est vide</h2>
            </div>
        <?php endif; ?>

    </div>

<?php require 'footer/footer.php' ?>
