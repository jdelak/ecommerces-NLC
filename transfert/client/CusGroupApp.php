<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class CusGroupApp extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface
{
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_objectManager;

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */


    public function launch()
    {


        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_storeManager = $_objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        $state = $_objectManager->create('\Magento\Framework\App\State');
        $state->setAreaCode('frontend');
        $websiteId = $_storeManager->getWebsite()->getWebsiteId();
        $customers = $_objectManager->create('\Magento\Customer\Model\Customer');
        $customers->setWebsiteId($websiteId);
        /*
        $resource = $_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        */

        $customerCollection = $customers->getCollection()->setPageSize(10000)->setCurPage(9);
 
        var_dump(count($customerCollection));
        try{
            foreach($customerCollection as $customer){
                /*
                $customerID = $customer->getId();
                $req = "SELECT value FROM customer_entity_varchar WHERE attribute_id = 141 AND entity_id = '$customerID'";
                $connection->query($req);
                $codeCli=$connection->fetchAll($req);
                */


                $row = $this->getGcustomerGesco($customer->getEmail());
                if($customer->getGroupId() == 1 && $row['gclremi'] != 0){
                    //var_dump($customer->getData());
                    //LXL 10
                    if($row['gclremi'] == 10.00){$customer->setGroupId(9);}
                    // LXL 15
                    if($row['gclremi'] == 15.00){$customer->setGroupId(10);}
                    // LXL 20
                    if($row['gclremi'] == 20.00){$customer->setGroupId(11);}
                    // LXL 25
                    if($row['gclremi'] == 25.00){$customer->setGroupId(6);}
                    // LXL 30
                    if($row['gclremi'] == 30.00){$customer->setGroupId(7);}
                    // LXL 50
                    if($row['gclremi'] == 50.00){$customer->setGroupId(8);}
                    $customer->save();
                    echo $customer->getEmail(). ' mis à jour<br>';
                }else{
                    echo $customer->getEmail(). ' n\'a pas besoin d\'être mis à jour<br>';
                }
            }


        }catch (\Exception $e) {
            var_dump(($e->getMessage()));
        }

    }

    public function getGcustomerGesco($email){

        $bdd = new PDO('mysql:host=localhost;dbname=image_gesco_RO','root','Kay8lWiBMQwu');
    $sql = $bdd->query("SELECT gclcode, gclremi FROM gcliact WHERE gclmail = '$email'");



        $results = $sql->fetch();
        return $results;
    }
    public function getGcustomerGescoPriv(){

        $bdd = new PDO('mysql:host=localhost;dbname=image_gesco_RO','root','Kay8lWiBMQwu', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $sql = $bdd->query("SELECT gclcode,gclremi FROM `gcliact` WHERE `gcliact`.`gclremi` > 0");



        $results = $sql->fetch();
        return $results;
    }


}