<?php


namespace Sage\Export\Cron;

class Export
{

    protected $logger;
    private $pdo;

    private $fichierClient;
    private $fichierCmd;
    private $fichierAbo;

    private $PLXCMDNETCLI;	# Fichier des clients
    private $PLXCMDNETTRADENT;	# Fichier des entetes de BC
    private $PLXABONETTRADENT;	# Fichier des entetes d'Abo

    private $folder = '/var/www-virtual/ecommerce/keops/app/code/Sage/Export/File/';

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->pdo = new \PDO('mysql:dbname=e_keops;host=localhost', 'root', 'Kay8lWiBMQwu');

        $this->fichierClient = 'SAGECLISITE'.date('Ymd').'.TXT';
        $this->fichierCmd = 'SAGECMDSITE'.date('Ymd').'.TXT';
        //$this->fichierAbo = 'SAGEABOSITE'.date('Ymd').'.TXT';
        $this->PLXCMDNETCLI = fopen('/var/www-virtual/ecommerce/keops/app/code/Sage/Export/File/'.$this->fichierClient,'a');
        $this->PLXCMDNETTRADENT = fopen('/var/www-virtual/ecommerce/keops/app/code/Sage/Export/File/'.$this->fichierCmd,'a');
        //$this->PLXABONETTRADENT = fopen('/var/www-virtual/ecommerce/keops/app/code/Sage/Export/File/'.$this->fichierAbo,'a');
        $this->CustomersTest = array('116231', '172696', '172865', '54200', '173319', '175153', '177323', '89279', '175154', '175152', '1', '2', '139688', '173086', '175151');

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $listOrder = $this->getOrder();
        foreach ($listOrder as $value){
            $orderTest = 1;
            if(!in_array($value['customer_id'],$this->CustomersTest)) {
                $this->writeCustomer($value);
                $this->writeOrder($value);
                $orderTest = 0;
            }
            $this->setCommandeExporte($value['entity_id'], $orderTest);
        }

        $this->putRepository();
        $this->logger->addInfo("Cronjob export is executed.");
    }

    private function getOrder(){
        //$query = "SELECT * FROM `sales_order` WHERE increment_id = '6000000007'";
        $query = "SELECT * FROM `sales_order` WHERE status = 'processing' AND store_id = 7";
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

    private function getCustomerAddress($idOrder){
        $query = " SELECT address.* FROM `sales_order` 
                   INNER JOIN sales_order_address address ON address.entity_id = sales_order.shipping_address_id
                   WHERE sales_order.`entity_id` = $idOrder";
        $result = $this->pdo->query($query);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    private function writeCustomer($infos){

        $code = $infos['customer_id'];
        $infosCustomer = $this->getCustomer($code);
        $codeGesco = $this->getCodeCustomerGesco($code);
        $icCustomer = substr($infosCustomer['increment_id'], 3);
        if($codeGesco){
            $icCustomer = $codeGesco['value'];
        }
        $infosCustomerAddress = $this->getCustomerAddress($infos['entity_id']);
        $nom = $infosCustomer['firstname'].' '.$infosCustomer['lastname'];
        $mail = $infosCustomer['email'];
        $tel1 = $infosCustomerAddress['telephone'];
        $tel2 = '';
        $addresses = explode("\n", $infosCustomerAddress['street']);
        $adr1 = strtoupper($addresses[0]);
        $adr2 = '';
        if(strpos($infosCustomerAddress['street'], "\n")){
            $adr2 = strtoupper($addresses[1]);
        }
        $cpos = $infosCustomerAddress['postcode'];
        $vill = strtoupper($infosCustomerAddress['city']);
        $pays = $infosCustomerAddress['country_id'];

        $banque = "";
        $codeBanque = "";
        $codeGuichet = "";
        $numCompte = "";
        $cle = "";
        $devise = "";

        $cb = '';
        $cvc = '';
        $dateExp = '';
        $source = '';

        fwrite($this->PLXCMDNETCLI,utf8_decode("$icCustomer|411000|$nom|$nom|$adr1|$adr2|$cpos|$vill|$pays|$tel1|$tel2|$mail|$banque|$codeBanque|$codeGuichet|$numCompte|$cle|$devise|$cb|$dateExp|$cvc|$source;"));
    }

    private function writeOrder($infos){


        $numClient = $infos['customer_id'];
        $infosCustomer = $this->getCustomer($infos['customer_id']);
        $codeGesco = $this->getCodeCustomerGesco($numClient);
        $icCustomer = substr($infosCustomer['increment_id'], 3);
        if($codeGesco){
            $icCustomer = $codeGesco['value'];
        }
        $numCmd = '6TN'.substr($infos['increment_id'], 4);
        $orderId = $infos['entity_id'];
        $shippingAmount = round($infos['base_shipping_amount'],2);
        $sqlDate = strtotime($infos['created_at']);
        $date = date('dmy', $sqlDate);

        $produits = $this->getOrderItems($orderId);
        $remise = 0;


        if($shippingAmount>0){
            fwrite($this->PLXCMDNETTRADENT,utf8_decode("6|$numCmd|$date|$icCustomer|ZPORT|Port & frais d'expédition|Pièce|1|$shippingAmount|0||1;"));
        }


        foreach ($produits as $produit) {
            $refProd = $produit['sku'];
            $quantite = round($produit['qty_ordered']);
            $design = $produit['name'];
            $prixttc = round($produit['price'],2);
            fwrite($this->PLXCMDNETTRADENT,utf8_decode("6|$numCmd|$date|$icCustomer|$refProd|$design|Pièce|$quantite|$prixttc|$remise||1;"));
        }
        return true;

    }

    private function getOrderItems($orderId){
        $query = "SELECT * FROM `sales_order_item` WHERE order_id = $orderId";
        return $this->pdo->query($query);
    }

    private function setCommandeExporte($orderId, $test){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId);
        if($test==0){
            $order->setState("exported")->setStatus("exported");
            $order->save();
        }else{
        $order->setState("archive")->setStatus("archive");
        $order->save();
        }
        return true;
    }

    private function putRepository(){
        var_dump(shell_exec('sh '.\getenv('LIB_NTIC').'/STARGATE/lxputstg.sh EXPEDITION/TIME_NUTRITION/SAGE '.$this->folder.substr($this->fichierClient, 0, -4).' TXT N'));
        var_dump(shell_exec('sh '.\getenv('LIB_NTIC').'/STARGATE/lxputstg.sh EXPEDITION/TIME_NUTRITION/SAGE '.$this->folder.substr($this->fichierCmd, 0, -4).' TXT N'));
        /*unlink($this->folder.$this->fichierClient);
        unlink($this->folder.$this->fichierCmd);*/
    }
}
