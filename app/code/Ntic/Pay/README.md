    ################################################################
	##################### 	   MODULE PAY 		####################
	################################################################

	=== DESCRIPTION DU MODULE ===

        Permet au client de payer avec leur CB
        et d'envoyer les informations à Payzen
        ( Pour l'instant exclusivement avec les boutiques en ligne e-commerce classique )
        

	=== BASE DE DONNEE ===
	 _______________
    |				|
    |	 certif		|
    |_______________|
    | certif_id 	|
    | shopId		|
    | certifTest	|
    | certProd		|
    | ctxMode		|
    | wsdl			|
    | ns 			|
    | store			|
    |_______________|

    === CONTROLLER ===

    -= ADMIN =-
        Delete     
        Edit
        Index
        InlineEdit
        NewAction
        Save

    -= FRONT =-
        Index           => Gestion et rendu de la vue.
        Submit          => Gestion et envoie du form.

    === HELPER ====
        ° Payzen        => Librairie pour la Gestion du SOAP de Payzen ["https://payzen.io/fr-FR/module-de-paiement-gratuit/#"]

    === BLOCK ===
        ° Form          => Vérification des données.
        ° SecurePayment => Récupère le contrat / devis (quote) en cours.

    ##################### version #######################

	===		V1.0.1 		===

	Utiliser le SoapV5 amélioré sans perturbation et en utilisant l'id v4
	Modification dans Helper/Payzen/SoapV5 (line 214 - 218)
	Enlever les sleep(1) lors du paiement

	===    V1.0.2       ===

    Retirer le javascript / jquery dans le template "securepayment_form.phtml".

    ===		V1.0.3 		===

	Ajouter le prix total à payer à côté du bouton "Payer la commande"

    ##################### README OFFICIEL PAYZEN #####################

    # SOAP vV5 payment exemple using PHP
    Example of PHP code using [PayZen](https://payzen.eu/) SOAP V5 webservices - createPayment request
    Please refer to the documentation website [payzen.io](https://payzen.io/fr-FR/webservices-payment/implementation-webservices-v5/historique-du-document.html)

    ## Introduction
    The code presented here is a demonstration of the implementation of the SOAP v5 PayZen webservices, aimed to ease its use and learning.

    This code only supports the `createPayment` request, but shows how a PayZen request and its answer can be handled.


    ## Contents
    This code is divided in three parts:
    * create-payment.php, the main file, entry point of the process
    * payzenSoapV5ToolBox.php, the core file, defining an utility class encapsulating all the PayZen logics
    * UUID.php, an utility tool handling the generation of valid UUID, took from [https://gist.github.com/dahnielson/508447](https://gist.github.com/dahnielson/508447).


    ## The first use
    1. Place the files on the same directory
    2. In `payzenBootstrap.php`, replace the occurences of `[***CHANGE-ME***]` by the actual values of your PayZen account
    3. Execute:
    > php example/create-payment.php
    to perform the createPayment request, in "TEST" mode.


    ## The next steps
    You can follow the on-file documentation in `create-payment.php` to change the properties of the payment you want to initiate, like the amount or the informations of the customer payment card.

    You will also find here the instructions on how to plug the toolbox logging process to your own, and finally, you can change the `TEST` parameter to `PRODUCTION` to switch to _real_ payment mode, with *all* the caution this decision expects.


    ## Note
    * The documentation used to write this code was [Guide technique d’implémentation des Web services V5, v1.4](https://payzen.eu/wp-content/uploads/2015/09/Guide_technique_d_implementation_Webservice_V5_v1.4_Payzen.pdf) (FRENCH)

    ===		V1.0.3.1 		===

    Ajout des logos CB prises en compte dans le choix du mode paiement :
    Ntic/Pay/view/frontend/web/template/payment/securepayment.html