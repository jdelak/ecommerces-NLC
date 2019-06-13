<?php


namespace Gesco\Export\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{

    private $fileHeadingOrder;
    private $fileDetailOrder;
    private $fileEcheanceOrder;
    private $fileTotalOrder;
    private $fileHeadingSubcription;
    private $fileCustomer;
    private $CustomersTest;

    private $folder = '/var/www-virtual/ecommerce/keops/app/code/Gesco/Export/File/';

    private $pdo;

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->pdo = new \PDO('mysql:dbname=e_keops;host=localhost', 'root', 'Kay8lWiBMQwu');

        /*$this->fileHeadingOrder = fopen($this->folder.'cde_ent.txt','a');
        $this->fileDetailOrder = fopen($this->folder.'cde_det.txt','a');
        //$this->fileEcheanceOrder = fopen($this->folder.'cde_ech.txt','a');
        $this->fileTotalOrder = fopen($this->folder.'cde_tot.txt','a');
        //$this->fileHeadingSubcription = fopen($this->folder.'abo_ent.txt','a');
        $this->fileCustomer = fopen($this->folder.'export_client.txt','a');*/
        $this->CustomersTest = array(/*'116231', '172696', '172865', '54200', '173319', '175153', '177323', '89279', '175154', '175152', '1', '2', '139688', '173086', '175151'*/);
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $listOrder = $this->getOrder();
        $listOrderDota = $this->getOrderDota(); //liste des order en dotation
        foreach ($listOrder as $value){
            $orderTest = 1;
            if(!in_array($value['customer_id'],$this->CustomersTest)){
                $this->writeHeadingOrder($value);
                $this->writeDetailOrder($value);
                $this->writeTotalOrder($value);
                $this->writeCustomer($value);
                $orderTest = 0;
            }
            $this->setCommandeExporte($value['entity_id'], $orderTest);
        }

        foreach ($listOrderDota as $value){
            $orderTest = 0;
            $this->writeHeadingOrder($value);
            $this->writeDetailOrder($value);
            $this->writeTotalOrder($value);
            $this->writeCustomer($value);
            $this->setCommandeExporte($value['entity_id'], $orderTest);
        }

        exit;
        //$this->putRepository();
        //this->logger->addInfo("Cronjob Export is executed.");
    }
        private function getOrder(){
        //$query = "SELECT * FROM `sales_order` WHERE store_id = 3 AND increment_id = '3008017049'";
        $query = "SELECT * FROM `sales_order` WHERE status = 'processing' AND store_id = 3 AND increment_id >= '3008000000'";
        return $this->pdo->query($query);
    }

        //recupère la liste des order en dotation
        private function getOrderDota(){
        $query = "SELECT * FROM `sales_order` WHERE store_id = 4 AND shipping_method = 'owsh1_dota_method' AND = 'processing'";
        return $this->pdo->query($query);
    }

    private function getCustomer($idCustomer){
        $query = "SELECT * FROM `e_keops`.`customer_entity` WHERE `entity_id` = $idCustomer";
        $result = $this->pdo->query($query);
        return $result->fetch(\PDO::FETCH_ASSOC);

    }

    private function getCodeCustomerGesco($idCustomer){
        $query = "SELECT value FROM `e_keops`.`customer_entity_varchar` WHERE `entity_id` = $idCustomer AND attribute_id = 141";
        $result = $this->pdo->query($query);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    private function getOrderShippingAddress($idOrder){
        $query = "SELECT * FROM `sales_order_address` WHERE `parent_id` = $idOrder AND address_type = 'shipping'";
        $result = $this->pdo->query($query);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    private function getCustomerAddress($idOrder){
        $query = "SELECT * FROM `customer_address_entity` WHERE `parent_id` = $idOrder";
        $result = $this->pdo->query($query);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    private function getPaymentMethod($idOrder){
        $query = "SELECT method FROM sales_order_payment WHERE parent_id = $idOrder";
        $result = $this->pdo->query($query);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    private function writeCustomer($infos){

        $code = $infos['customer_id'];
        $infosCustomer = $this->getCustomer($code);
        $infosCustomerAddress = $this->getCustomerAddress($code);
        $nom = strtoupper($infosCustomer['lastname']);
        $prenom = strtoupper($infosCustomer['firstname']);
        $prefix = $infosCustomer['prefix'];
        $codeGesco = $this->getCodeCustomerGesco($code);
        //var_dump($codeGesco);
        $icCustomer = substr($infosCustomer['increment_id'], 2);
        if($codeGesco){
            $icCustomer = $codeGesco['value'];
        }
        $gender = 'F';
        //Gestion des prefix
        if ($prefix == 'Mr') {
            $gender = 'M';
        }
        if ($prefix == 'Mme') {
            $gender = 'F';
        }
        if ($prefix == 'Mlle') {
            $gender = 'L';
        }

        $birth = $infosCustomer['dob'];
        $mail = $infosCustomer['email'];
        $tel1 = $infosCustomerAddress['telephone'];
        $tel2 = '';
        $tel3 = '';
        $adr1 = strtoupper($infosCustomerAddress['street']);
        $adr2 = '';
        $adr3 = '';
        $cpos = $infosCustomerAddress['postcode'];
        $vill = strtoupper($infosCustomerAddress['city']);
        $country = $infosCustomerAddress['country_id'];

        $paysFormat = '73';
        if($country=='FR'){
            $paysFormat = '73';
        }
        $InfosCustomCodeConseilWebF = '';

        //fwrite($this->fileCustomer,utf8_decode("O|$icCustomer|$gender|$prenom|$nom|$birth|$mail|$tel1|$tel2|$tel3|$adr1|$adr2|$adr3|$cpos|$vill|$paysFormat|$InfosCustomCodeConseilWebF|N|\n"));
    }

    private function writeHeadingOrder($infos){

        $numCustomer = $infos['customer_id'];
        $numOrder = $infos['increment_id'];
        $icOrder = substr($numOrder, 3);
        $orderId = $infos['entity_id'];
        $shippingAmount = $infos['base_shipping_amount'];
        $date = $infos['created_at'];
        $modeExpe = "EX";
        $RelaisColisRF = '';



        $infosCustomer = $this->getCustomer($numCustomer);
        $icCustomer = substr($infosCustomer['increment_id'], 2);
        $infosCustomerAddress = $this->getOrderShippingAddress($orderId);
        $prefix = $infosCustomer['prefix'];

        $gender = 'F';
        //Gestion des prefix
        if ($prefix == 'Mr') {
            $gender = 'M';
        }
        if ($prefix == 'Mme') {
            $gender = 'F';
        }
        if ($prefix == 'Mlle') {
            $gender = 'L';
        }
        $lastname = strtoupper($infosCustomerAddress['lastname']);
        $fistname = strtoupper($infosCustomerAddress['firstname']);
        $addresses = explode("\n", $infosCustomerAddress['street']);
        $adr1 = strtoupper($addresses[0]);
        //var_dump($addresses);
        $adr2 = '';
        if(strpos($infosCustomerAddress['street'], "\n")){
            $adr2 = $addresses[1];
        }

        $adr3 = '';
        //echo $adr2;
        $cpos = $infosCustomerAddress['postcode'];
        $city = strtoupper($infosCustomerAddress['city']);
        $country = $infosCustomerAddress['country_id'];
        $paysFormat = '73';
        if($country=='FR'){
            $paysFormat = '73';
        }
        $EtapeCheque1EnTete2RF = '';
        $EtapeCheque2EnTete2RF = '';


        $paymentMethod = $this->getPaymentMethod($orderId)['method'];

        //gestion des méthodes de paiements
        if ($paymentMethod == 'payzen_standard') {
            $paymentMethod = 'CB';
        } else if ($paymentMethod == 'paypal_standard' || $paymentMethod == 'paypal_express') {
            $paymentMethod = 'PP';
        }


        $remoteIpEntF = '';
        $coupon_code = $infos['coupon_code'];
        $remise = round(abs($infos['base_discount_amount']),2);
        echo $remise;

        //fwrite($this->fileHeadingOrder,utf8_decode("$icOrder|$icCustomer|$date|$paymentMethod|$EtapeCheque1EnTete2RF|$EtapeCheque2EnTete2RF|$gender|$lastname|$fistname|$adr1|$adr2|$adr3|$city|$cpos|$paysFormat|0|$remoteIpEntF|$coupon_code|$modeExpe|$RelaisColisRF|\n"));



        return true;

    }

    private function writeDetailOrder($infos){
        $numOrder = $infos['increment_id'];
        $icOrder = substr($numOrder, 3);
        $orderId = $infos['entity_id'];
        $produits = $this->getOrderItems($orderId);
        $compteurDetail = 0;
        foreach ($produits as $produit) {
            $refProd = $produit['sku'];
            $quantite = $produit['qty_ordered'];
            $prixttc = $produit['price'];
            if($infos['shipping_method'] != "owsh1_dota_method"){
                $retrive_ht_price = round($prixttc/1.2, 2);
            } else {
                $retrive_ht_price = 0.00;
            }
            var_dump($numOrder." ".$retrive_ht_price);
            $TaxDetailFinal = '20.0000';
            $CodeSortie = '';
            $compteurDetail++;
            //fwrite($this->fileDetailOrder,utf8_decode("$compteurDetail|$icOrder|$refProd|$retrive_ht_price|$retrive_ht_price|$TaxDetailFinal|$quantite|$CodeSortie|\n"));
        }

        return true;
    }


    private function writeTotalOrder($infos){
        $numOrder = $infos['increment_id'];
        $icOrder = substr($numOrder, 3);
        $orderId = $infos['entity_id'];
        $totalOrder =  $infos['base_grand_total'];
        $shippingAmount = $infos['base_shipping_amount'];
        $compteurTotaux = 1;
        //variable de frais de port
        $texteFraisPorts = 'FRAIS DE PORT (OFFERTS) :';
        if ($shippingAmount == '7.000') {
            $texteFraisPorts = 'FRAIS DE PORT (NORMAUX) :';
        }
        if ($shippingAmount == '4.99') {
            $texteFraisPorts = 'FRAIS DE PORT (REDUITS) :';
        }
        if ($shippingAmount == '0.000') {
            $texteFraisPorts = 'FRAIS DE PORT (OFFERTS) :';
        }
        $amountTax = $totalOrder*0.20;
        $subTotal = $totalOrder - $shippingAmount;
        fwrite($this->fileTotalOrder,utf8_decode($compteurTotaux++."|$icOrder|$texteFraisPorts|$shippingAmount|OT_SHIPPING|\n"));
        fwrite($this->fileTotalOrder,utf8_decode($compteurTotaux++."|$icOrder|TAX 20 :|$amountTax|OT_TAX|\n"));
        fwrite($this->fileTotalOrder,utf8_decode($compteurTotaux++."|$icOrder|SOUS-TOTAL :|$subTotal|OT_SUBTOTAL|\n"));
        /*
         *  ajouter les codes cheques cadeau
         */
        fwrite($this->fileTotalOrder,utf8_decode($compteurTotaux++."|$icOrder|TOTAL :|$totalOrder|OT_TOTAL|\n"));
        return true;
    }

    /* private function writeOrderEcheance($infos){
         fwrite($this->fileTotalOrder,utf8_decode("$compteurTotaux++."|$icOrder|SOUS-TOTAL :|$totalOrder|OT_TOTAL|\n"));
     }*/

    private function writeSubsciption($num)
    {
        $infoabo = $this->fetch("SELECT * FROM lxctrabo WHERE catvte =$num");
        $numAbo = $infoabo['cancon'];
        $numClient = $this->getClient($num);

        $date = $infoabo['cadtcr'];
        $date2 = implode('',array_reverse(explode('-', $date)));
        $date3= substr($date2, 0,4).substr($date2, 6,2);

        $numAboSage = "6TN".$numAbo;

        $refAbo = $infoabo['carefa'];
        $design = $this->fetch("SELECT * FROM abonnements WHERE aboref = '$refAbo'")['abolib'];
        $prixttc = '';
        if($refAbo == '9301'){
            $prixttc = $infoabo['camens'];
        }
        else{
            $prixttc = $infoabo['camens']*$infoabo['canbmo'];
        }

        fwrite($this->PLXABONETTRADENT,utf8_decode("6|$numAboSage|$date3|$numClient|$refAbo|$design|Pièce|1|$prixttc|1;"));





        $this->setAbonnementExporte($numAbo);
        return true;
    }

    private function getOrderItems($orderId){
        $query = "SELECT * FROM `sales_order_item` WHERE order_id = $orderId";
        return $this->pdo->query($query);
    }

    private function setCommandeExporte($orderId, $test){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId);
        echo $orderId;
        if($test==0){
            echo "pas test";
            $order->setState("processing")->setStatus("exported");
            $order->save();
        }else{
            echo "test";
            $order->setState("processing")->setStatus("archive");
            $order->save();
        }
        return true;

    }

    private function putRepository(){
       /* var_dump(shell_exec('sh '.\getenv('LIB_NTIC').'/STARGATE/lxputstg.sh EXPEDITION/TIME_NUTRITION/EN_ATTENTE '.$this->folder.'cde_ent txt O'));
        var_dump(shell_exec('sh '.\getenv('LIB_NTIC').'/STARGATE/lxputstg.sh EXPEDITION/TIME_NUTRITION/EN_ATTENTE '.$this->folder.'cde_det txt O'));
        var_dump(shell_exec('sh '.\getenv('LIB_NTIC').'/STARGATE/lxputstg.sh EXPEDITION/TIME_NUTRITION/EN_ATTENTE '.$this->folder.'cde_tot txt O'));
        var_dump(shell_exec('sh '.\getenv('LIB_NTIC').'/STARGATE/lxputstg.sh EXPEDITION/TIME_NUTRITION/EN_ATTENTE '.$this->folder.'export_client txt O'));*/
    }
}
