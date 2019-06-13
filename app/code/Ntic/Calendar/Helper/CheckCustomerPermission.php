<?php

namespace Ntic\Calendar\Helper;
/**
 * Class CheckCustomerPermission
 * @package Ntic\Calexndar\Helper
 * Va contenir les methodes de recupération des informations du customer connecté stockés en SESSION
 */
class CheckCustomerPermission
{
    /**
     * Vérifie si l'utilisateur fais partie u groupe "manager"
     * @return bool
     */
    public static function isManager()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        //var_dump($customerSession->getData());die();
        $user_group_id = $customerSession->getData('customer_group_id');

        if ($user_group_id != 4) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed NOM DE L'UTILISATEUR
     */
    public static function userInfos()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        return $customerSession->getCustomer()->getName();
    }

    /**
     * Id customer en SESSION
     * @return mixed
     */
    public static function getUserId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        return $customerSession->getData('customer_id');
    }
    /**
     * Retourne le Code seller de la personne connecter [E0123]
     * @return mixed
     */
    public static function getUserSellerCode()
    {
        // Instance OM (pas l'olympique => object manager)
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // Connection Datebase
        $ressource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        // Recuperation du modele SESSIONS
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        // Recuperation de l'id customer connecter
        $customer_id = $customerSession->getData('customer_id');
        // Connection a la base de données MAGENTO2
        $connection = $ressource->getConnection();
        // Récupère la table
        $tableCustomer =$ressource->getTableName('customer_entity');

        $sql = "SELECT customer_entity.entity_id, customer_entity.group_id ,
                        customer_entity.firstname, 
                        customer_entity.lastname,email, 
                        customer_entity_varchar.value,
                        customer_entity_varchar.attribute_id
                        FROM " . $tableCustomer . " 
                        LEFT JOIN  
                        customer_entity_varchar ON customer_entity_varchar.entity_id = ".$tableCustomer.".entity_id
                        WHERE customer_entity.entity_id = " .$customer_id. " AND customer_entity_varchar.attribute_id = 143 ";
        $res = $connection->query($sql)->fetch();

        return  $res['value'];
    }

}