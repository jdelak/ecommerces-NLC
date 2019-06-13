<?php

namespace Ntic\UpdateCustomer\Controller\Yvane;

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
        \Ntic\UpdateCustomer\Helper\Data $helperData,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
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
        $this->customerCollectionFactory  = $customerCollectionFactory;
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
        echo '<form action="" method="post" id="test6">
                <input type="hidden" name="customerTN" value="1">
                <button type="submit">Customer TN</button>
              </form>
              <form action="" method="post" id="test5">
                <input type="hidden" name="order" value="1">
                <button type="submit">order Gesco</button>
              </form>
              ';
        $this->registry->register('isSecureArea', true);
        if (isset($_POST['order']) && $_POST['order'] == '1') {
            $this->addOrder();
        }elseif(isset($_POST['customerTN']) && $_POST['customerTN'] == '1') {
            $res = $this->getCustomerTN();
            foreach ($res as $re) {
                list($nom, $prenom) = explode(' ', $re['intitule']);
                $item=[
                    'created_at'    => date('d/m/Y'),
                    'updated_at'    => date('d/m/Y'),
                    'tel'           => $re['tel'],
                    'email'         => $re['email'],
                    'firstname'     => $prenom,
                    'lastname'      => $nom,
                    'street'        => $re['adresse'],
                    'city'          => $re['ville'],
                    'postcode'      => $re['cp'],
                    'prefix'        => '',
                    'codeGesco'     => $re['num_client'],
                    'mobile'        => '0',
                    'gender'        => '',
                    'pays'          => 'FR',
                ];
                $this->addCustomer2($item,3, 'FR');


            }

        }elseif(isset($_POST['customer']) && $_POST['customer'] == '1'){
            // delete etrangers
            /*$res = $this->getCustomerMasterNOTFR();
            foreach ($res as $re) {
                $this->DeleteCustomer($re['entity_id']);
            }*/

            // ajout customer
            //var_dump($this->addCustomer());
            /*echo '
            <script>
                document.getElementById("test").submit();
            </script>';*/
        }else if(isset($_POST['customerBE']) && $_POST['customerBE'] == '1'){
            $res = $this->getCustomerMasterBE();

            foreach ($res as $re) {
                $this->addCustomer2($re);
            }
        }else if(isset($_POST['customerCH']) && $_POST['customerBE'] == '1'){
            $res = $this->getCustomerMasterCH();
            var_dump($res);
            /*foreach ($res as $re) {
                $this->addCustomer($re);
            }*/
        }else if(isset($_POST['addseller']) && $_POST['addseller'] == '1'){
            $this->addSeller();
        }elseif(isset($_POST['adresse']) && $_POST['adresse'] == '1'){
            // choose customer
            $collection = $this->customerCollectionFactory->create();
            foreach ($collection as $customer){
                $customer->getId();
            }
            //var_dump($collection->getData());
            // select customer
        }
        /*$customerRepository=$this->_objectManager->get('Magento\Customer\Model\ResourceModel\CustomerRepository');
        $customer = $customerRepository->getById(4);
        $encryptor = $this->_objectManager->get('\Magento\Framework\Encryption\Encryptor');
        $customerRepository->save($customer, $encryptor->getHash('Lexel2017', true));*/
        /*for ($i=196;$i<= 198;$i++){
            var_dump($this->deleteOrder('3000000'.$i));
        }*/
    }
    public function addOrder(){

        $websiteId = $this->_storeManager->getWebsite()->getWebsiteId();
        $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $values = $connection->fetchAll('   SELECT c.* , v.value as codeclientG
                                            FROM `customer_entity_varchar` v, customer_entity c
                                            LEFT JOIN customer_address_entity a ON c.entity_id = a.parent_id
											WHERE c.entity_id = v.entity_id 
											AND c.website_id = 2
                                            AND v.attribute_id = 141 
                                            LIMIT 100000,61000');

        foreach($values as $key =>$customerObjdata ){
            $registery = $this->_objectManager->create('Magento\Framework\Registry');
            $registery->register('isSecureArea','true');
            $orders = $this->SuperGetOrder($customerObjdata['codeclientG'],$customerObjdata['entity_id']);
            if(count($orders)>0){
                try {
                    foreach ($orders as  $order) {
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
                                'street' => [$order['gcladr1'], $order['gcladr2'].', '.$order['gcladr3'], ],
                                'city' => $order['gclvill'],
                                'country_id' => $order['gclpays'],
                                'region' => intval(substr($order['gclcpos'], 0, 2)) + 182,
                                'postcode' => $order['gclcpos'],
                                'telephone' => $tel,
                                'save_in_address_book' => 0
                            ],
                            'items' =>  //array of product which order you want to create
                                $orderItems
                        ];
                        var_dump($key);
                        if($orderData['shipping_address']['postcode'] == '98000'){ // MONACO CA MAMAN
                            $orderData['shipping_address']['postcode'] = '98000';
                            $orderData['shipping_address']['country_id'] = 'FR';
                            $orderData['shipping_address']['region'] = 183+42;
                        }

                        $res = $this->helperData->createMageOrder($orderData);
                        //var_dump($res);
                    }

                } catch (Exception $e) {
                    $e->getMessage();
                    var_dump($orderData);exit;
                }
            }
            $registery->unregister('isSecureArea');
        }

    }

    public function deleteOrder($id){
        $registery = $this->_objectManager->get('Magento\Framework\Registry');

        $order =$this->_objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($id);
        $registery->register('isSecureArea','true');
        $order->delete();
        $registery->unregister('isSecureArea');
    }
    public function addCustomer2($item,$website,$country){
        var_dump($item);die();
        $registery = $this->_objectManager->create('Magento\Framework\Registry');
        $registery->register('isSecureArea','true');
        list($d,$m,$y) = explode('/',$item['created_at']);
        $item['created_at']=$y.'-'.$m.'-'.$d;
        list($d,$m,$y) = explode('/',$item['updated_at']);
        $item['updated_at']=$y.'-'.$m.'-'.$d;
        $item['tel'] = ($item['tel'] != '')?$item['tel']:'0';
        $item['mobile'] = ($item['mobile'] != '')?$item['mobile']:'0';
        $item['fax'] = ($item['fax'] != '')?$item['fax']:'0';
        $region = intval(substr($item['postcode'], 0, 2)) + 182;
        list($add1,$add2) = explode(CHR(10),$item['street']);
        $item['street'] = array($add1,$add2);

        //-------------------------------> Création du client
        $customer = $this->_objectManager->create('\Magento\Customer\Model\Customer');
        $websiteId = $this->_storeManager->getWebsite()->getWebsiteId($website);
        $customer->setWebsiteId($website);
        $customerExist = $customer->loadByEmail($item['email']);
        if ($item['lastname'] == '' || $item['firstname'] == '') {
            echo '<h1>Warning</h1> Customer ' . $item['email'] . ' no firstname or name <br>';
            echo '<audio src="https://translate.google.fr/translate_tts?ie=UTF-8&q=Erreur%20d%27avertissement&tl=fr&total=1&idx=0&textlen=22&tk=29583.398662&client=t" autoplay></audio>';exit();
        } else if (!filter_var($item['email'], FILTER_VALIDATE_EMAIL)) {
            echo '<h1>Warning</h1> Customer ' . $item['email'] . ' f****** email <br>';
            echo '<audio src="https://translate.google.fr/translate_tts?ie=UTF-8&q=Erreur%20d%27avertissement&tl=fr&total=1&idx=0&textlen=22&tk=29583.398662&client=t" autoplay></audio>';exit();
        } else if ($customerExist->getId()) {
            echo 'website '.$websiteId ;
            var_dump($item);
            echo '<h1>Warning</h1> Customer ' . $item['email'] . ' already exists <br>';
            echo '<audio src="https://translate.google.fr/translate_tts?ie=UTF-8&q=Erreur%20d%27avertissement&tl=fr&total=1&idx=0&textlen=22&tk=29583.398662&client=t" autoplay></audio>';exit();
        } else {
            if($item['street']==''){
                $item['street']=' . ';
            }
            if($item['city']==''){
                $item['city']=' . ';
            }
            if($item['postcode']==''){
                $item['postcode']='01000';
            }
            $customer->setEmail($item['email']);
            $customer->setPrefix($item['prefix']);
            $customer->setFirstname($item['firstname']);
            $customer->setLastname($item['lastname']);
            $customer->setData('code_client_gesco', $item['codeGesco']);


            $customer->setData('mobile', $item['mobile']);
            $customer->setGender($item['gender']);

            $customer->setData('created_at', $item['created_at']);
            $customer->setData('updated_at', $item['updated_at']);
            //$customer->setSellerNumber($item[7]);
            $customer->setWebsiteId($website);
            $customer->save();
            //
            //echo 'create customer successfully ' . $item['email'] . '<br>';
            //--------------------------------->ajout de l'addresse du client
            $address = $this->_objectManager->create('\Magento\Customer\Model\Address');
            $address->setCustomerId($customer->getId());

            $address->setFirstname($item['firstname']);
            $address->setLastname($item['lastname']);
            $address->setCountryId($item['pays']);
            $address->setRegion($region);

            $address->setPostcode($item['postcode']);
            $address->setCity($this->encodeY($item['city']));
            $address->setTelephone($item['tel']);

            $address->setCompany();

            $address->setStreet($item['street']);

            $address->setIsDefaultBilling('1');
            $address->setIsDefaultShipping('1');
            $address->setSaveInAddressBook('1');
            $address->save();

            /*echo $customer->getData('email');
            echo 'address created for customer ' . $item[0] . '<br>';*/
            //$this->UpdateCustomerMaster($item['codeGesco'],$website);
        }
    }
    public function addCustomer(){
        $registery = $this->_objectManager->create('Magento\Framework\Registry');
        $registery->register('isSecureArea','true');
        $compteur = file_get_contents("var/cpt.txt");
        $max = intval($compteur) + 2;
        try{

            $store = $this->_storeManager->getStore();
            $storeId = $store->getStoreId();
            // CUSTOMER MASTER

            for($i=$compteur;$i<$max;$i++) {
                $item = $this->getCustomerMaster($i);
                $item['tel'] = ($item['tel'] != '')?$item['tel']:'0';
                $item['mobile'] = ($item['mobile'] != '')?$item['mobile']:'0';
                $item['fax'] = ($item['fax'] != '')?$item['fax']:'0';
                $region = intval(substr($item['postcode'], 0, 2)) + 182;

                if($item['Source'] == 'GESCO'){
                    $fileWebsite = 2;
                }elseif ($item['Source'] == 'TN_FR'){
                    $fileWebsite = 3;
                }elseif ($item['Source'] == 'COSMETEL'){
                    $fileWebsite = 6;
                }else
                    $fileWebsite = 2;
                //-------------------------------> Création du client
                $customer = $this->_objectManager->create('\Magento\Customer\Model\Customer');
                $websiteId = $this->_storeManager->getWebsite()->getWebsiteId($fileWebsite);
                $customer->setWebsiteId($fileWebsite);
                $customerExist = $customer->loadByEmail($item['email']);
                if ($item['lastname'] == '' || $item['firstname'] == '') {
                    echo '<h1>Warning</h1> Customer ' . $item['email'] . ' no firstname or name <br>';
                    echo '<audio src="https://translate.google.fr/translate_tts?ie=UTF-8&q=Erreur%20d%27avertissement&tl=fr&total=1&idx=0&textlen=22&tk=29583.398662&client=t" autoplay></audio>';exit();
                } else if (!filter_var($item['email'], FILTER_VALIDATE_EMAIL)) {
                    echo '<h1>Warning</h1> Customer ' . $item['email'] . ' f****** email <br>';
                    echo '<audio src="https://translate.google.fr/translate_tts?ie=UTF-8&q=Erreur%20d%27avertissement&tl=fr&total=1&idx=0&textlen=22&tk=29583.398662&client=t" autoplay></audio>';exit();
                } else if ($customerExist->getId()) {
                    echo 'website '.$websiteId ;
                    var_dump($item);
                    echo '<h1>Warning</h1> Customer ' . $item['email'] . ' already exists <br>';
                    echo '<audio src="https://translate.google.fr/translate_tts?ie=UTF-8&q=Erreur%20d%27avertissement&tl=fr&total=1&idx=0&textlen=22&tk=29583.398662&client=t" autoplay></audio>';exit();
                } else {
                    if($item['street']==''){
                        $item['street']=' . ';
                    }
                    if($item['city']==''){
                        $item['city']=' . ';
                    }
                    if($item['postcode']==''){
                        $item['postcode']='01000';
                    }
                    $customer->setEmail($item['email']);
                    $customer->setPrefix($item['prefix']);
                    $customer->setFirstname($item['firstname']);
                    $customer->setLastname($item['lastname']);
                    $customer->setData('code_client_gesco', $item['codeGesco']);


                    $customer->setData('mobile', $item['mobile']);
                    $customer->setGender($item['gender']);

                    $customer->setData('created_at', $item['created_at']);
                    $customer->setData('updated_at', $item['updated_at']);
                    //$customer->setSellerNumber($item[7]);
                    $customer->setWebsiteId($fileWebsite);
                    $customer->save();
                    //echo 'create customer successfully ' . $item['email'] . '<br>';
                    //--------------------------------->ajout de l'addresse du client
                    $address = $this->_objectManager->create('\Magento\Customer\Model\Address');
                    $address->setCustomerId($customer->getId());

                    $address->setFirstname($this->encodeY($item['firstname']));
                    $address->setLastname($this->encodeY($item['lastname']));
                    $address->setCountryId('FR');
                    $address->setRegion($region);

                    $address->setPostcode($item['postcode']);
                    $address->setCity($this->encodeY($item['city']));
                    $address->setTelephone($item['tel']);

                    $address->setCompany();

                    $address->setStreet($this->encodeY($item['street']));

                    $address->setIsDefaultBilling('1');
                    $address->setIsDefaultShipping('1');
                    $address->setSaveInAddressBook('1');
                    $address->save();
                    /*echo $customer->getData('email');
                    echo 'address created for customer ' . $item[0] . '<br>';*/
                    $this->UpdateCustomerMaster($item['codeGesco'],$item['Source']);
                }
                file_put_contents("var/cpt.txt",$i);
            }
        }catch (Exception $e) {
            echo '<h1>Warning</h1>';
            var_dump($item);
            //var_dump($e->getMessage());
        }
        $registery->unregister('isSecureArea');

    }
    public function addSeller(){
        $registery = $this->_objectManager->create('Magento\Framework\Registry');
        $registery->register('isSecureArea','true');

        $store = $this->_storeManager->getStore();
        $storeId = $store->getStoreId();
        $allSeller = $this->getAllSeller();
        try{
            foreach ($allSeller as $item) {
                $customerTest = $this->_objectManager->create('\Magento\Customer\Model\Customer');
                $customerTest->setWebsiteId(2);
                $customerExist = $customerTest->loadByEmail($item['gremail']);
                if($item["grecode"]!=''){
                    if (!filter_var($item['gremail'], FILTER_VALIDATE_EMAIL)) {
                        //echo '<h1>Warning</h1> Customer ' . $item['gremail'] . ' f****** email <br>';
                    } else if ($customerExist->getId()) {
                        // echo 'website ' ;
                        //echo '<h1>Warning</h1> Customer ' . $item['gremail'] . ' already exists <br>';
                    } else {
                        $item['gretele']    = ($item['gretele'] != '')?$item['gretele']:'0';
                        $item['gretel2']    = ($item['gretel2'] != '')?$item['gretel2']:'0';
                        $item['grefax']     = ($item['grefax'] != '')?$item['grefax']:'0';
                        if($item['greadr1']==''){
                            $item['greadr1']=' . ';
                        }
                        if($item['grevill']==''){
                            $item['grevill']=' . ';
                        }
                        if($item['grecpos']==''){
                            $item['grecpos']='01000';
                        }
                        $region = intval(substr($item['grecpos'], 0, 2)) + 182;
                        $customer = $this->_objectManager->create('\Magento\Customer\Model\Customer');

                        $customer->setWebsiteId(2);
                        $customer->setEmail($item['gremail']);
                        $customer->setPrefix($item['gretitr']);
                        $customer->setFirstname($item['grepren']);
                        $customer->setLastname($item['grenom']);

                        $customer->setData('mobile', $item['gretel2']);
                        $customer->setGender(2);

                        $customer->setData('created_at', $item['gredtcr']);
                        $customer->setData('updated_at', $item['gredtcr']);
                        $customer->setSellerNumber($item['grecode']);
                        $customer->setGroupId(4);
                        $customer->save();
                        //echo 'create customer successfully ' . $item['email'] . '<br>';
                        //--------------------------------->ajout de l'addresse du client
                        $address = $this->_objectManager->create('\Magento\Customer\Model\Address');
                        $address->setCustomerId($customer->getId());

                        $address->setFirstname($this->encodeY($item['grepren']));
                        $address->setLastname($this->encodeY($item['grenom']));
                        $address->setCountryId('FR');
                        $address->setRegion($region);

                        $address->setPostcode($item['grecpos']);
                        $address->setCity($this->encodeY($item['grevill']));
                        $address->setTelephone($item['gretele']);

                        $address->setCompany();

                        $address->setStreet($item['greadr1'],$item['greadr2']);

                        $address->setIsDefaultBilling('1');
                        $address->setIsDefaultShipping('1');
                        $address->setSaveInAddressBook('1');
                        $address->save();
                        var_dump($customer->getData());
                    }
                }
            }
        }catch (Exception $e) {
            echo '<h1>Warning</h1>';
            var_dump($e->getMessage());
        }
        $registery->unregister('isSecureArea');
    }

    public function encodeY($chaine)
    {
        //$string = iconv ('ASCII', 'UTF8//TRANSLIT', $chaine);
        $string = preg_replace ('#[^.0-9a-z],-\'+#i', ' ', $chaine);
        return $string;
    }

    public function getCustomerMaster($cpt){
        $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $sql = $bdd->query("SELECT * FROM customer_m WHERE exportM = 0  LIMIT ".$cpt." , 1");
        //$sql = $bdd->query("SELECT * FROM customer_m WHERE exportM = 0 AND email ='cecilesotomayor@hotmail.fr'  " );
        $results = $sql->fetch();
        return $results;
    }
    public function getCustomerMasterNOTFR(){
        $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $sql = $bdd->query("SELECT * FROM customer_z WHERE pays != 'FR' AND exportM = 1 ");
        //$sql = $bdd->query("SELECT * FROM customer_m WHERE exportM = 0 AND email ='cecilesotomayor@hotmail.fr'  " );
        $results = $sql->fetchAll();
        return $results;
    }
    public function getCustomerMasterBE(){
        $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $sql = $bdd->query("SELECT * FROM customer_z WHERE pays = 'BE' AND exportM = 0 ");
        //$sql = $bdd->query("SELECT * FROM customer_m WHERE exportM = 0 AND email ='cecilesotomayor@hotmail.fr'  " );
        $results = $sql->fetchAll();
        return $results;
    }
    public function UpdateCustomerMaster($code,$source){
        $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu');
        $result = $bdd->exec("UPDATE customer_z SET exportM = 1 WHERE website_id = '$source' AND codeGesco = '$code'");
        return $result;
    }

    public function DeleteCustomer($id){

        $customer = $this->_objectManager->create('\Magento\Customer\Model\Customer');
        $customer->load($id);
        if($customer->getID()){
            $customer->delete();
            $bdd = new \PDO('mysql:host=localhost;dbname=master_old','root','Kay8lWiBMQwu');
            $result = $bdd->exec("UPDATE customer_z SET exportM = 0 WHERE entity_id = '$id'");
            return $result;
        }
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

    public function getCustomerTN(){
        $bdd = new \PDO('mysql:host=localhost;dbname=TN_ventespro','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $sql = $bdd->query("SELECT * FROM alliance_customer limit 0,10");
        $results = $sql->fetchAll();
        return $results;
    }

    public function getCustomerInGesco(){
        $bdd = new \PDO('mysql:host=localhost;dbname=e_keops','root','Kay8lWiBMQwu');
        $sql = $bdd->query("SELECT customer_entity.entity_id , website_id, customer_entity_varchar.value
                FROM `customer_entity` , customer_entity_varchar 
                WHERE customer_entity_varchar.entity_id = customer_entity.entity_id 
                AND customer_entity_varchar.attribute_id = 141
                AND website_id = 2");
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
            $suppl_sql = "";
        }
        $req_order = "SELECT gcencde, gcecli, gcladr1, gcladr2, gcladr3, gclcpos, gclvill, gclpays, gcltele, gcltel2, gexnfac, gextras, gcedsai 
                      FROM gcdeent 
                      INNER JOIN gcliact ON gcdeent.gcecli = gcliact.gclcode 
                      INNER JOIN gcdeexp ON gcdeent.gcencde = gcdeexp.gexncde 
                      WHERE gcecli ='".$codeclient."' 
                      AND gcemttc > 0 ".$suppl_sql;
        $bdd = new \PDO('mysql:host=localhost;dbname=image_gesco_RO','root','Kay8lWiBMQwu');
        $sql = $bdd->query($req_order);
        $order_a=NULL;
        if($sql){
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
    // portfolio
    public function getAllSeller(){
        $bdd = new \PDO('mysql:host=localhost;dbname=image_gesco_RO','root','Kay8lWiBMQwu', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $sql = "SELECT * FROM `grepres` WHERE grecomm = 'PRESENTE' AND gremail != '' AND grecode NOT IN ('0', 'VP01') LIMIT 150,500";
        $results1 = $bdd->query($sql);
        $results2 = $results1->fetchAll();
        return $results2;
    }
}

?>
