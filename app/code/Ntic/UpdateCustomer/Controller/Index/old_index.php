<?php

namespace Ntic\UpdateCustomer\Controller\Index;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $connectionFactory;
    protected $_storeManager;
    protected $productRepository;
    protected $stockRegistry;
    protected $_indexerCollectionFactory;
    protected $categoryFactory;
    protected $_objectManager;
    protected $registry;
    protected $helperData;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\App\ResourceConnection\ConnectionFactory $connectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Indexer\Model\Indexer\CollectionFactory $indexerCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Ntic\UpdateCustomer\Helper\Data $helperData
        // \Magento\Catalog\Model\Category $category

    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->connectionFactory = $connectionFactory;
        $this->_storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->stockRegistry = $stockRegistry;
        $this->_indexerCollectionFactory = $indexerCollectionFactory;
        $this->registry = $registry;
        $this->helperData = $helperData;
        $this->_objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        echo 'begin run';
        echo '<form action="" method="post">
                <input type="hidden" name="customer" value="1">
                <button type="submit">customer</button>
              </form>';
        if(isset($_POST['customer']) && $_POST['customer'] == '1'){
            var_dump($this->addCustomer());
        }
        /*$customerRepository=$this->_objectManager->get('Magento\Customer\Model\ResourceModel\CustomerRepository');
        $customer = $customerRepository->getById(4);
        $encryptor = $this->_objectManager->get('\Magento\Framework\Encryption\Encryptor');
        $customerRepository->save($customer, $encryptor->getHash('Lexel2017', true));*/
        /*for ($i=196;$i<= 198;$i++){
            var_dump($this->deleteOrder('3000000'.$i));
        }*/
    }

    public function deleteOrder($id){
        $registery = $this->_objectManager->get('Magento\Framework\Registry');

        $order =$this->_objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($id);
        $registery->register('isSecureArea','true');
        $order->delete();
        $registery->unregister('isSecureArea');
    }
    public function addCustomer(){
        $registery = $this->_objectManager->create('Magento\Framework\Registry');
        $registery->register('isSecureArea','true');
        $compteur = file_get_contents("var/cpt.txt");
        $max = intval($compteur) + 1;
        try{
            $websiteId = $this->_storeManager->getWebsite()->getWebsiteId();
            $store = $this->_storeManager->getStore();
            $storeId = $store->getStoreId();
            // CUSTOMER MASTER
            for($i=$compteur;$i <= $max;$i++) {
                $item = $this->getCustomerMaster(1);
                var_dump($item);die();
                $region = intval(substr($item[8], 0, 2)) + 182;
                $mail = ($item[15] == '') ? 'nomail_'. $item[0] . '@lexel.fr' : $item[15];
                $mobile = '';
                if ($item[12] != '') {
                    $tel = $item[12];
                    $mobile = ($item[13] == '') ? $item[14] : $item[13];
                } elseif ($item[12] == '' && $item[13] != '') {
                    $tel = $item[13];
                } elseif ($item[12] == '' && $item[13] == '' && $item[14] != '') {
                    $tel = $item[14];
                } else {
                    $tel = '0';
                }
                $data = [
                    $mail,
                    trim($item[17]),
                    trim($item[2]),
                    trim($item[4]),
                    null,
                    trim($item[3]),
                    trim($item[0]),
                    null,
                    trim($item[28]),
                    trim($item[8]),
                    trim($item[9]),
                    trim($item[11]),
                    [$item[5], $item[6], $item[7]],
                    $region,
                    trim($tel),
                    trim($mobile)
                ];
                //-------------------------------> Création du client
                $customer = $this->_objectManager->create('\Magento\Customer\Model\Customer');
                $customer->setWebsiteId($websiteId);
                $customerExist = $customer->loadByEmail($mail);

                if ($data[3] == '' || $data[5] == '') {
                    echo 'Customer ' . $data[0] . ' no firstname or name <br>';
                } else if (!filter_var($data[0], FILTER_VALIDATE_EMAIL)) {
                    echo 'Customer ' . $data[0] . ' f****** email <br>';
                } else if ($customerExist->getId()) {
                    echo 'Customer ' . $data[0] . ' already exists <br>';
                } else {
                    $customer->setEmail($data[0]);
                    $customer->setPrefix($data[2]);
                    $customer->setFirstname($data[3]);
                    $customer->setLastname($data[5]);
                    $customer->setData('code_client_gesco', $data[6]);
                    $customer->setData('mobile', $data[15]);
                    if ($data[2] == 'MR') {
                        $customer->setGender(1);
                    } else {
                        $customer->setGender(2);
                    }
                    $customer->save();
                    //$customer->setSellerNumber($data[7]);
                    $customer->save();
                    echo 'create customer successfully ' . $data[0] . '<br>' ;
                    //--------------------------------->ajout de l'addresse du client
                    $address = $this->_objectManager->create('\Magento\Customer\Model\Address');
                    $address->setCustomerId($customer->getId());

                    $address->setFirstname($data[3]);
                    $address->setLastname($data[5]);
                    $address->setCountryId('FR');
                    $address->setRegion($data[13]);

                    $address->setPostcode($data[9]);
                    $address->setCity($data[10]);
                    $address->setTelephone($data[14]);

                    $address->setCompany();
                    $address->setStreet($data[12]);

                    $address->setIsDefaultBilling('1');
                    $address->setIsDefaultShipping('1');
                    $address->setSaveInAddressBook('1');
                    var_dump($address->getData());
                    var_dump($address->save());die();
                    echo 'address created for customer ' . $data[0] . '<br>';
                }
            }

        }catch (Exception $e) {
            var_dump($e->getMessage());
        }
        var_dump($compteur);
        exit();
        $registery->unregister('isSecureArea');

    }
    public function getCustomerMaster($cpt){
        $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu');
        $sql = $bdd->query("SELECT * FROM customer LIMIT ".$cpt." , 1");
        //SELECT * FROM gcliact WHERE gclddcd >= '2010-01-01' AND gclnom <> 'CNIL' AND gclmail = '' AND gcltele = '' AND gcltel2 = '' AND gclfax = '' ORDER BY `gcliact`.`gcladr1` ASC
        $results = $sql->fetch();
        return $results;
    }
    public function addOrder(){
        $registery = $this->_objectManager->create('Magento\Framework\Registry');
        $registery->register('isSecureArea','true');
        $websiteId = $this->_storeManager->getWebsite()->getWebsiteId();
        $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $values = $connection->fetchAll('   SELECT c.* , v.value as codeclientG
                                            FROM `customer_entity_varchar` v, customer_entity c
                                            LEFT JOIN customer_address_entity a ON c.entity_id = a.parent_id
											WHERE c.entity_id = v.entity_id 
                                            AND v.attribute_id = 141 limit 1,1');
        foreach($values as $customerObjdata ){
            $orders = $this->SuperGetOrder($customerObjdata['codeclientG'],$customerObjdata['entity_id']);

            if(count($orders)>0){

                try {
                    foreach ($orders as $order) {
                        //var_dump($order);die();
                        $orderItems=array();
                        foreach ($order['ligne'] as $ligne) {
                            $productID= $this->getProductBySKU($ligne['gcaprod']);
                            $orderItems[]=['product_id' => $productID, 'qty' => (int)$ligne['gcaqtec'], 'price' => (float)$ligne['gpcprxv']];
                        }

                        $tel = ($order['gcltele']!='')?$order['gcltele']:'0';
                        $orderData = [
                            'gesco_no'   => $order['gcencde'],
                            'created_at' => $order['gcedsai'],
                            'currency_id' => 'EUR',
                            'email' => $customerObjdata['email'], //buyer email id
                            'shipping_address' => [
                                'firstname' => $customerObjdata['firstname'], //address Details
                                'lastname' => $customerObjdata['lastname'],
                                'street' => [$order['gcladr1'], $order['gcladr2'], $order['gcladr3']],
                                'city' => $order['gclvill'],
                                'country_id' => $order['gclpays'],
                                'region' => '',
                                'postcode' => $order['gclcpos'],
                                'telephone' => $tel,
                                'save_in_address_book' => 0
                            ],
                            'items' =>  //array of product which order you want to create
                                $orderItems
                        ];
                        $res = $this->helperData->createMageOrder($orderData);
                        var_dump($res);
                    }

                } catch (Exception $e) {
                    $e->getMessage();
                }
            }
        }
        $registery->unregister('isSecureArea');
    }

    public function updateAddress(){
        //$this->registry->register('isSecureArea', true);
        $websiteId = $this->_storeManager->getWebsite()->getWebsiteId();
        $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $values = $connection->fetchAll('   SELECT c.* , v.value as codeclientG
                                            FROM `customer_entity_varchar` v, customer_entity c
                                            LEFT JOIN customer_address_entity a ON c.entity_id = a.parent_id
											WHERE a.parent_id IS NULL
                                            AND c.entity_id = v.entity_id 
                                            AND v.attribute_id = 141 limit 1000');
        foreach($values as $customerObjdata ){
            $result = $this->getCustomerGesco($customerObjdata['codeclientG']);

            if($result){
                /*var_dump($customerObjdata);
                var_dump($result);die();*/
                if($result['gclnom'] == 'CNIL'){
                    /*
                    $customer = $this->_objectManager->create('\Magento\Customer\Model\Customer');
                    $customer->setWebsiteId($websiteId);
                    $customerExist = $customer->loadByEmail($customerObjdata['email']);
                    $customerExist->delete();
                    echo $customerObjdata['entity_id'].' -> delete ----- <br>';
                    */
                }elseif($result['gcladr1'] != ''){
                    $addr1 = utf8_encode($result['gcladr1']);
                    $addr2 = utf8_encode($result['gcladr2']);
                    $addr3 = utf8_encode($result['gcladr3']);
                    $street = [$addr1,$addr2,$addr3];
                    $cp    = $result['gclcpos'];
                    $ville = utf8_encode($result['gclvill']);
                    $first = $customerObjdata['firstname'];
                    $last = $customerObjdata['lastname'];
                    if($result['gcltele']!=''){
                        $tel  = $result['gcltele'];
                    }elseif($result['gcltel2']!=''){
                        $tel  = $result['gcltel2'];
                    }elseif($result['gclfax']!=''){
                        $tel  = $result['gclfax'];
                    }else{
                        $tel = '0';
                    }

                    $fax  =$result['gclfax'];
                    $idcustomer = $customerObjdata['entity_id'];

                    $address = $this->_objectManager->create('\Magento\Customer\Model\Address');

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
                        echo $customerObjdata['entity_id'].' -> OK <br>';
                    }catch (Exception $e) {
                        Zend_Debug::dump($e->getMessage());
                        echo $customerObjdata['entity_id'].' -> ko XXXX <br>';
                    }


                }
            }
        }
    }
    public function getCustomerGesco($codeclient){
        $bdd = new \PDO('mysql:host=localhost;dbname=image_gesco_RO','root','Kay8lWiBMQwu');
        $sql = $bdd->query("SELECT * FROM gcliact WHERE gclcode = '$codeclient'");
        $results = $sql->fetch();

        return $results;
    }
    public function getProductBySKU($sku){
        $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $values = $connection->fetchOne("  SELECT *
                                        FROM `catalog_product_entity` 
                                        WHERE `sku` LIKE '$sku'");
        return $values;
    }


    public function SuperGetOrder($codeclient,$id){
        $order_a = array();
        $req_check_cmd_in_m2 = "SELECT Gesco_Order_Origin FROM `sales_order` s
                                INNER JOIN customer_entity c on c.entity_id = s.customer_id
                                where s.customer_id = ".$id." ";
        $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $cmd_gescos = $connection->fetchAll($req_check_cmd_in_m2);
        $tablo=array();
        foreach ($cmd_gescos as $cmd_gesco) {
            $tablo[]=$cmd_gesco['Gesco_Order_Origin'];
        }
        $tabloWithVir = implode(",",$tablo);

        if(count($tablo)>0){
            $suppl_sql = "AND gcdeent.gcencde NOT IN (".$tabloWithVir.")";
        }else{
            $suppl_sql="";
        }
        $req_order = "SELECT gcencde, gcecli, gcladr1, gcladr2, gcladr3, gclcpos, gclvill, gclpays, gcltele, gcltel2, gexnfac, gextras, gcedsai 
                      FROM gcdeent 
                      INNER JOIN gcliact ON gcdeent.gcecli = gcliact.gclcode 
                      INNER JOIN gcdeexp ON gcdeent.gcencde = gcdeexp.gexncde 
                      WHERE gcecli ='".$codeclient."' 
                      AND gcemttc > 0 ".$suppl_sql;
        $bdd = new \PDO('mysql:host=localhost;dbname=image_gesco_RO','root','Kay8lWiBMQwu');
        $sql = $bdd->query($req_order);
        $orders = $sql->fetchAll();
        foreach ($orders as $order) {
            $req_lig = "SELECT  gcaprod, gcaqtec, gcamttc , gpcprxv
                        FROM gcdelig 
                        INNER JOIN gprocam ON gcdelig.gcaprod = gprocam.gpcprod 
                        WHERE  gcaqtec >= 0 
                        AND  gcdelig.gcancde = '".$order['gcencde']."' 
                        AND gpccamp = 1
                        AND gcaqtec >= 0";
            $sql = $bdd->query($req_lig);
            $ligne = $sql->fetchAll();
            $order['ligne']=$ligne;
            $order_a[]=$order;
        }

        return $order_a;
    }
    //récupère chaque ligne de produit de toutes les commandes d'un client en fonction de son code code client
    public function getOrderLineGesco($codeclient){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $bdd = $objectManager->create('Magento\Framework\App\ResourceConnection\ConnectionFactory')->create(array(
            'host' => 'localhost',
            'dbname' => 'image_gesco_RO',
            'username' => 'root',
            'password' => 'Kay8lWiBMQwu',
            'active' => '1',
        ));

        $sql = 'SELECT gcencde, gcecli, gcladr1, gcladr2, gcladr3, gclcpos, gclvill, gclpays, gcltele, gcltel2, gcaprod, gcaqtec, gcamttc FROM gcdeent LEFT JOIN gcdelig ON gcdeent.gcencde = gcdelig.gcancde LEFT JOIN gcliact ON gcdeent.gcecli = gcliact.gclcode WHERE gcecli ='.$codeclient.'  AND gcaqtec >= 0';

        $results = $bdd->fetchAll($sql);
        return $results;
    }

    //récupère toutes les commandes d'un client en fonction de son code client
    public function getOrderGesco($codeclient){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $bdd = $objectManager->create('Magento\Framework\App\ResourceConnection\ConnectionFactory')->create(array(
            'host' => 'localhost',
            'dbname' => 'image_gesco_RO',
            'username' => 'root',
            'password' => 'Kay8lWiBMQwu',
            'active' => '1',
        ));

        $sql2 = 'SELECT gcencde, gcecli, gcladr1, gcladr2, gcladr3, gclcpos, gclvill, gclpays, gcltele, gcltel2, gcaprod, gcaqtec, gcamttc FROM gcdeent LEFT JOIN gcdelig ON gcdeent.gcencde = gcdelig.gcancde LEFT JOIN gcliact ON gcdeent.gcecli = gcliact.gclcode WHERE gcecli ='.$codeclient.'  AND gcaqtec >= 0 GROUP BY gcencde';

        $results2 = $bdd->fetchAll($sql2);
        return $results2;
    }
}

?>
