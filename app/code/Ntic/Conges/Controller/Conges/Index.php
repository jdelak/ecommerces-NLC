<?php

namespace Ntic\Conges\Controller\Conges;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class Index extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {

        //Instance de ObjectManager
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //Recupere la session pour connaitre l'utilisateur connecter
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        $customerid = $customerSession->getCustomer()->getId(); // Id utilisateur connecter
        // Informations de la base de données Magento
        $resource = $objectManager ->get('Magento\Framework\App\ResourceConnection');
        $_db = $resource->getConnection(); // Connexion
        $tableName = $_db->getTableName('ntic_conges_conges'); // Table dur laquelle on requete
        // Requete pour récupérer tout les congès de l'utilisateur connecté
        $sql = $_db->select()
            ->from($tableName)
        ->where('demandeur_id ='.$customerid);
        // Execute la requete
        $data = $_db->fetchAll($sql);
        // Echo le résutlat au format Json pour l'exploitation par Datatable.js (view)
        echo json_encode( array( 'data' => $data) );
        die();
        /**
         * Obligé de mettre un die
         * Exception: Warning: Cannot modify header information
         * - headers already sent by (output started at /var/www-virtual/ecommerce/keops/app/code/Ntic/Conges/Controller/Conges/Index.php:28)
         * in /var/www-virtual/ecommerce/keops/vendor/magento/framework/Stdlib/Cookie/PhpCookieManager.php on line 125
         * in /var/www-virtual/ecommerce/keops/vendor/magento/framework/App/ErrorHandler.php:61
         * Stack trace:
        */
    }


}