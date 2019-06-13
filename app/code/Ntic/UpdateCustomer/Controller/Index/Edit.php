<?php
namespace Ntic\UpdateCustomer\Controller\Index;

class Edit extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        var_dump($this->getCustomerMaster(1));
        /*
        SELECT c.website_id,master.well_master,master.customer_id , v.value as codeclientG
        FROM `customer_entity_varchar` v, customer_entity c,ntic_masterid_mastercode master
        WHERE c.entity_id = v.entity_id
        AND master.customer_id = c.entity_id
        AND v.attribute_id = 141
        */
    }
    public function getCustomerMaster($cpt){
        $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $sql = $bdd->query("SELECT * FROM customer_m WHERE exportM = 1 LIMIT ".$cpt." , 1");
        $results = $sql->fetch();
        return $results;
    }
}
