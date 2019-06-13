<?php


namespace Ntic\MasterId\Observer\Customer;

class SaveAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    )
    {
        // INIT
        $mastercode = NULL;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->create('\Magento\Framework\App\ResourceConnection');
        $Master = $objectManager->create('\Ntic\MasterId\Model\MasterCode');
        $customer = $observer->getCustomer();
        $messageManager = $objectManager->create('\Magento\Framework\Message\ManagerInterface');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        $customer_email = $customer->getEmail();
        $customer_id = $customer->getId();
        $website_id = $customer->getWebsiteId();

        $masterTable = $resource->getTableName('ntic_masterid_mastercode');
        $resultCheck = $connection->fetchRow('SELECT COUNT(*) as tot
                                              FROM `' . $masterTable . '` c 
                                              WHERE customer_id = ' . $customer_id . '; ');

        if ($resultCheck['tot'] > 0) {
            // Existe deja
        }else{
            // SQL
            $customerTable = $resource->getTableName('customer_entity');
            $resultCheck = $connection->fetchAll('SELECT c.entity_id,m.well_master, m.int_master                
                                          FROM `' . $customerTable . '` c 
                                          INNER JOIN ntic_masterid_mastercode m ON m.customer_id = c.entity_id 
                                          WHERE email = "' . $customer_email . '"');
            $total = count($resultCheck);
            // ------------------------------------------------> Si l'adresse mail existe déjà dans un autre website

            if ($total >= 1) {
                $int_master=$resultCheck[0]['int_master'];
                $Master->setCustomerId($customer_id);
                $Master->setWellMaster($int_master);
                $Master->setIntMaster($int_master);
                $Master->save();
            } else {// ------------------------------------------> Si l'adresse mail n'existe pas on crée un master ID
                $Master->setCustomerId($customer_id);
                $id = ($this->getIncrement()) + 1;
                $Master->setWellMaster($id);
                $Master->setIntMaster($id);
                $Master->save();
            }

        }
    }
    public function getIncrement()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $masterTable = $resource->getTableName('ntic_masterid_mastercode');
        $resultCheck = $connection->fetchRow('SELECT MAX(int_master) as max
                                              FROM `' . $masterTable . '` ; ');
        //Si il n'y a pas de client on commence l'id à 10000000
        if($resultCheck['max']==null){
            $return = 10000000;
        }else{
            $return = $resultCheck['max'];
        }
        return (int) $return;
    }
}
