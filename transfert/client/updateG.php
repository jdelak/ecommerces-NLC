<?php

    $result = getCustomerMaster(1);

    foreach ($result as $value){
        /* 1er */
/*
        $master_old = getCustomerMasterByMail($value['email'],$value['website_id']);
        var_dump($master_old);exit();
        $res = addAttributeCode($master_old['codeGesco'],$value['entity_id'],$master_old['mobile']);
*/
        // 2nd
        $resG=getCustomerMagento($value['website_id'],$value['codeGesco']);
        $getMaster=getCustomerMasterById($resG['entity_id']);
        //var_dump($getMaster['well_master']);
        setCustomerMaster($value['website_id'],$value['codeGesco'],$getMaster['well_master'],$getMaster['customer_id']);

    }
function getCustomerMagentoBAD(){
    $bdd = new \PDO('mysql:host=localhost;dbname=e_keops','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $res = $bdd->query("SELECT * 
                        FROM `customer_entity` 
                        WHERE group_id != 4 
                        AND entity_id NOT IN
                        (SELECT c.entity_id
                        FROM `customer_entity_varchar` v, customer_entity c
                        WHERE c.entity_id = v.entity_id 
                        AND v.attribute_id = 141)");
    $results = $res->fetchAll();
    return $results;
}

function getCustomerMasterByMail($email,$source){
    $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $sql = $bdd->query("SELECT * FROM customer_m WHERE email = '".$email."' AND website_id = '".$source."' ");
    $results = $sql->fetch();
    return $results;
}

function getCustomerMaster($cpt){
    $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $sql = $bdd->query("SELECT * FROM customer_m WHERE exportM = 1 AND increment_id = '' LIMIT 0, ".$cpt." ;");
    $results = $sql->fetchAll();
    return $results;
}
function addAttributeCode($code,$idM,$mobile){
    $mobile=($mobile=='')?0:$mobile;
    $bdd = new \PDO('mysql:host=localhost;dbname=e_keops','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $sql1 = $bdd->exec('INSERT INTO `customer_entity_varchar` (`value_id`, `attribute_id`, `entity_id`, `value`) VALUES (NULL, \'141\', \''.$idM.'\', \''.$code.'\');');
    var_dump($sql1);exit();
    $sql2 = $bdd->exec('INSERT INTO `customer_entity_varchar` (`value_id`, `attribute_id`, `entity_id`, `value`) VALUES (NULL, \'142\', \''.$idM.'\', \''.$mobile.'\');');
    return [$sql1,$sql2];
}
function getCustomerMagento($source,$code){
    $bdd = new \PDO('mysql:host=localhost;dbname=e_keops','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $sql = $bdd->query("SELECT c.website_id, v.value as codeclientG,c.entity_id 
                        FROM `customer_entity_varchar` v, customer_entity c
                        WHERE c.entity_id = v.entity_id
                        AND v.attribute_id = 141
                        AND v.value = '$code' 
                        AND c.website_id = '$source'");

    $results = $sql->fetch();
    return $results;

}
function getCustomerMasterById($id){
    $bdd = new \PDO('mysql:host=localhost;dbname=e_keops','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $sql = $bdd->query("SELECT well_master,customer_id
                        FROM `ntic_masterid_mastercode`
                        WHERE customer_id = '$id'");
    $results = $sql->fetch();
    return $results;

}
function setCustomerMaster($source,$code,$master,$idM){
    $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    $results = $bdd->exec("UPDATE customer_m
                            SET increment_id = '$master', entity_id = '$idM'
                            WHERE website_id = '$source' 
                            AND codeGesco = '$code'");
    return $results;
}
/*
 *
 * SELECT c.website_id,master.well_master,master.customer_id , v.value as codeclientG
        FROM `customer_entity_varchar` v, customer_entity c,ntic_masterid_mastercode master
        WHERE c.entity_id = v.entity_id
        AND master.customer_id = c.entity_id
        AND v.attribute_id = 141
*/