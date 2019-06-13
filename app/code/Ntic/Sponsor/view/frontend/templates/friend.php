<?php

$title = "parrainage";
$description = "";
$urlSubmit = $this->getUrl('*/*/Save');
$customerId = $block->getCustomerId();
$typeGodfather = "friends";
$typeCarte = 'LBOX';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $title; ?></title>
    <meta content="<?php echo $description; ?>" name="description">
    <!--<link rel="stylesheet" href="css/styles.css">-->
    <link rel="apple-touch-icon" sizes="57x57" href="img/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="img/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    <link rel="manifest" href="img/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="img/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</head>
<body>
<div>
    <div class="ventes" id="friend">
        <h2>Profitez de l’offre 1 formule <?php echo $typeCarte?> achetée = 1 Carte <?php echo $typeCarte?> OFFERTE !</h2>
        <div id='parrainer'>
            <h3>Voulez-vous offrir la Carte <?php echo $typeCarte?> à un(e) ami(e)?</h3>
            <form class="form-friend" method="post" role="form" action="<?php echo $urlSubmit; ?>">
                <div class="wrapper-friend">
                    <input type="hidden" name="main_contact_sponsor"  value="<?php echo $customerId;?>"/>
                    <input type="hidden" name="type_godfather"  value="<?php echo $typeGodfather;?>"/>
                    <div class="block-friend0">
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="friend-lastname">Nom *</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="contact_0[lastname]" id="friend-lastname_0" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="friend-firstname">Prénom *</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="contact_0[firstname]" id="friend-firstname_0" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="friend-lastname">Code postal</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="contact_0[zip_code]" id="friend-zip_code_0" value=""/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="friend-phone">Téléphone *</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="contact_0[phone]" id="friend-phone_0" value="" required/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="friend-params">Commentaire</label>
                            </div>
                            <div class="col-md-9">
                                <textarea rows="2" cols="50"  name="contact_0[params]" id="friend-params_0" class="form-control" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-sponsor-submit">
                    <input type="submit" class="btn btn-primary right btn-lg" value="Enregistrer">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<script type="text/javascript">
    var i = 0;
    require(['jquery', 'jquery/ui'], function($) {
        $('#bn-add-friend').on('click', function () {
            continuer = true;
            var Ertel = /^0[123456789][\s-]?([0-9]{2}[\s-]?){4}$/;
            var Ercp = /^\d{5}$/;
            erreurs = "Veuillez remplir ou corriger les champs suivants :\n";
            if(!$("#friend-lastname_"+i).val()){
             erreurs = erreurs.concat("	- nom\n");
             continuer = false;
             }

             if(!$("#friend-firstname_"+i).val()){
             erreurs = erreurs.concat("	- prénom\n");
             continuer = false;
             }

             if(!$("#friend-phone_"+i).val()){
             erreurs = erreurs.concat("	- téléphone\n");
             continuer = false;
             }

             if($("#friend-phone_"+i).val() && !Ertel.test($("#friend-phone_"+i).val())){
             erreurs = erreurs.concat("	- téléphone (il doit commencer par 01, 02, 03, 04, 05, 06, 07, 08 ou 09 et contenir 10 chiffres)\n");
             continuer = false;
             }

             if($("#friend-zip_code_"+i).val() && !Ercp.test($("#friend-zip_code_"+i).val())){
             erreurs = erreurs.concat("	- code postal (5 chiffres)\n");
             continuer = false;
             }
        });
    });
</script>
