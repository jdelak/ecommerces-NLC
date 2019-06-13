<?php
class updateCustomerViaGesco extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface
{
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_storeManager;
    protected $_objectManager;
    protected $customerFactory;
    protected $response_perso;


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
        echo "run begin \n<br>";

        $websiteId = $_storeManager->getWebsite()->getWebsiteId();
        $store = $_storeManager->getStore();
        $resource = $_objectManager->create('\Magento\Framework\App\ResourceConnection');
        $customerObj = $_objectManager->create('\Magento\Customer\Model\Customer');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $values = $connection->fetchAll('   SELECT c.* , v.value as codeclientG
                                            FROM `customer_entity_varchar` v, customer_entity c
                                            LEFT JOIN customer_address_entity a ON c.entity_id = a.parent_id
											WHERE a.parent_id IS NULL
                                            AND c.entity_id = v.entity_id 
                                            AND v.attribute_id = 141 limit 1');
        //$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->getCollection();
        foreach($values as $customerObjdata ){

            $result = $this->getCustomerGesco($customerObjdata['codeclientG']);
            if($result){
                $addr1 = $result['gcladr1'];
                $addr2 = $result['gcladr2'];
                $addr3 = $result['gcladr3'];
                $street = [$addr1,$addr2,$addr3];
                $cp    = $result['gclcpos'];
                $ville = $result['gclvill'];
                $first = $customerObjdata['firstname'];
                $last = $customerObjdata['lastname'];
                if($result['gcltele']!=''){
                    $tel  = $result['gcltele'];
                }elseif($result['gcltel2']!=''){
                    $tel  = $result['gcltel2'];
                }elseif($result['gclfax']!=''){
                    $tel  = $result['gclfax'];
                }else{
                    $tel = 0;
                }

                $fax  =$result['gclfax'];
                $idcustomer = $customerObjdata['entity_id'];

                $address = $_objectManager->create('\Magento\Customer\Model\Address');

                $address->setCustomerId($idcustomer);
                $address->setFirstname($first);
                $address->setLastname($last);
                $address->setCountryId('FR');
                $address->setPostcode($cp);
                $address->setCity($ville);
                if($tel!=''){
                    $address->setTelephone($tel);
                }else{
                    $address->setTelephone(0);
                }
                if($fax!=''){
                    $address->setFax($fax);
                }
                $address->setRegion(182);
                $address->setCompany();
                $address->setStreet($street);
                $address->setIsDefaultBilling('1');
                $address->setIsDefaultShipping('1');
                $address->setSaveInAddressBook('1');

                try {
                    var_dump($address->save());
                }catch (Exception $e) {
                    Zend_Debug::dump($e->getMessage());
                }
                var_dump($address->save());die();
                var_dump($address->getData());die();

            }
            var_dump($customerObjdata);
            die();

        }
        return $this->_response;
    }
    public function getCustomerGesco($codeclient){
        $bdd = new PDO('mysql:host=localhost;dbname=image_gesco_RO','root','Kay8lWiBMQwu');
        $sql = $bdd->query("SELECT * FROM gcliact WHERE gclcode = '$codeclient'");
        $results = $sql->fetch();
        return $results;
    }
    public function addAddress($idcustomer,$street,$cp,$ville,$last,$first,$tel,$fax){
        $_objectManager =  \Magento\Framework\App\ObjectManager::getInstance();

        $address = $_objectManager->create('\Magento\Customer\Model\Address');

        $address->setCustomerId($idcustomer);
        $address->setFirstname($first);
        $address->setLastname($last);
        $address->setCountryId('FR');
        $address->setPostcode($cp);
        $address->setCity($ville);
        if($tel!=''){
            $address->setTelephone($tel);
        }else{
            $address->setTelephone(0);
        }
        if($fax!=''){
            $address->setFax($fax);
        }

        $address->setCompany();
        $address->setStreet($street);
        $address->setIsDefaultBilling('1');
        $address->setIsDefaultShipping('1');
        $address->setSaveInAddressBook('1');
        try {
            $address->save();
        }catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
        }

        var_dump($address->getData());die();
    }
}
?>