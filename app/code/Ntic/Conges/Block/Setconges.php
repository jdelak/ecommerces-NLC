<?php

namespace Ntic\Conges\Block;
use Magento\Framework\View\Element\Template;

class Setconges extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }

    public function getUid() {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        $customerid = $customerSession->getCustomer()->getId();

        return $customerid;
    }

    public function checkConges($start_at, $end_at)
    {
        $userId = $this->getUid();

        $_db = new \PDO('mysql:host=localhost;dbname=calendar_ntic','calendar_ntic','v4rtuE4ZP8Ub');
        $tableName = 'events'; // Table dur laquelle on requete principale
        // Requete pour récupérer tout les congès de l'utilisateur connecté
        $sql = "SELECT * FROM ".$tableName." 
                WHERE ( start_date BETWEEN '".$start_at."' AND '".$end_at."' OR end_date BETWEEN '".$start_at."' AND '".$end_at."' ) AND userId=".$userId." AND type != 5
                     ";
        // Execute la requete
        return $_db->query($sql)->fetchAll();

    }
}

