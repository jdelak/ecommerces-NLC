<?php
namespace Ntic\PayCustom\Controller\Index;

ini_set('display_errors', 1);
error_reporting(E_ALL);
use Magento\Framework\App\ObjectManager;
use Ntic\Pay\Helper\Payzen\Config;
use Ntic\Pay\Helper\Payzen\SoapV5;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Controller\ResultFactory;
use Ntic\PayCustom\Helper\CustomOrder\CreateOrder;
use Ntic\PayCustom\Helper\Navision\CreateOrderNAV;
use Ntic\PayCustom\Helper\Navision\NTLMStream;
use Ntic\PayCustom\Helper\Navision\NTLMSoapClient;


class Submit extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_checkoutSession;
    protected $customerSession;
    protected $resultRedirectFactory;
    protected $cartManagement;
    protected $_objectManager;
    protected $resultFactory;
    protected $config_bank = Config::PAYZEN_CONFIG;
    protected $_productRepository;
    protected $pdo;

    /**
     * Constructor
     * Permet de executer le script simple pour un paiment personnalisé sur PAYZEN
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     *
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface
    ){
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->_checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        //$this->_objectManager = $context->getObjectManagerInterface();
        $this->productRepositoryInterface = $productRepositoryInterface;

        $this->pdo = new \PDO('mysql:dbname=e_keops2;host=localhost', 'root', 'Kay8lWiBMQwu');

        $this->resultFactory = $context->getResultFactory();
        parent::__construct($context);
    }

    public function execute(){
        $this_item             = NULL;
        $customerId            = $this->_objectManager->create('Magento\Customer\Model\Session')->getCustomer()->getID();
        $orderData             = $abo_product =array();
        $quote                 = $this->_checkoutSession->getQuote();
        $payment               = $quote->getPayment();
        $post                  = $this->getRequest()->getPostValue();
        $additionalInformation = array();
        /*var_dump($post);
        exit;*/
        // #########################  ABONNEMENT   ##########################
        if(isset($post['type_order']) && $post['type_order'] == 'subscription') {
            // SAVE ORDER FOR ABONNEMENT
            foreach ($post['form_details'] as $abo){
                $this->changeGroupCustomer($abo['order_sku']);
                 $lonely=true;
                // recherche si le produit est présent dans la quote
                // recherche si l'abonnement est seul dans le panier ou s'il y a d'autre produit
                //var_dump($quote);
                foreach ($quote->getItems() as $key => $value){
                    $product = $value->getProduct();

                    if ($value->getSku() == $abo['order_sku']){
                        $this_item=$value['item_id'];
                    }else{
                        $lonely=false;
                    }
                }
            //$payment = $quote->getPayment();
            // --------------> SI l'abonnement n'est pas seul dans la commande
            if($lonely == false){
                $product = $this->productRepositoryInterface->get($abo['order_sku']);//get current product
               // Creation d'order pour l'abonnement
               $order_saved = $this->saveAbo($quote,$abo,$product->getId(),$post['form_subscription'][0][0]['mode_payment'] );
               if($order_saved){
                   $order_id = $order_saved['order_id'];
                   $order      = $this->_objectManager->create('Magento\Sales\Model\Order')->load($order_id);
                   $new_quote  = $this->_objectManager->create('Magento\Quote\Model\QuoteRepository')->get($order->getQuoteId());
                   $items      = $new_quote->getAllItems();
                   //$result = $this->saveDetailV2($order_id,$abo,$items[0]);



                   $this->saveOrder($order, $product);

                   // suppresssion de l'item du panier
                   $this->deleteItemThisCard($quote,$this_item);
                   // bug panier qui ne s'update pas
                   $this->_checkoutSession->clearQuote()->clearStorage();
                   $this->_checkoutSession->clearQuote();
                   $this->_checkoutSession->clearStorage();
                   $this->_checkoutSession->clearHelperData();
                   $this->_checkoutSession->resetCheckout();
                   $this->_checkoutSession->restoreQuote();
                   // redirection vers custompayment et message success
                   $this->messageManager->addSuccess(__('Congratulation, success order'));
                   $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
                   //$result->setPath('checkout/onepage/success/');
                   $result->setPath('custompayment/');
                   return $result;
               }
           }else{ // ------> SI l'abonnement est seul dans la commande on reste avec la meme quote

            if($post['cb']['cardNumber']!=''){
                $this->save_Cb($post['cb'],$customerId,$payment);
            }
            $quote->setPaymentMethod('securepayment');
            $payment->importData(['method' => 'paymentcustom']);
            $payment->save();
            $quote->save();
            // TRANSFORMATION QUOTE ==> ORDER
            $quoteManagement = $this->_objectManager->create('\Magento\Quote\Api\CartManagementInterface');
            $this->_checkoutSession
                ->setLastQuoteId($quote->getId())
                ->setLastSuccessQuoteId($quote->getId())
                ->clearHelperData();
            $order = $quoteManagement->submit($quote);
            $order->getPayment()->save();
            $order->save();

                if($post['iban']['prefixe']!=''){
                    $this->save_Iban($post['iban'],$payment, $order->getId());
                }

            $i = 0;

            $order->getPayment()->setAdditionalInformation('type_order', $post['type_order']);

            $payment_Methods = array();
            foreach ($post['form_subscription'] as $tab){
                $expedition = array();
                $expedition['Shipping_Date'] = $tab['shipping_date'];
                foreach ($tab as $value){
                    if(isset($value['is_active']) && $value['is_active']=='true') {
                        $expedition[] = [
                            "Payment_Method" => $value['mode_payment'],
                            "Payment_Pct" => '',//$value['percentage'],
                            "Payed_Amount_Incl_VAT" => $value['amount'],
                            "Due_Date" => $value['date_parcel'] . '-' . $value['month_year_parcel'],
                            "Bank_Check_No" => $value['check']['number_check'],
                            "Bank_Name" => $value['check']['name_bank'],
                            "Bank_Check_Name" => $value['check']['owner_check']
                        ];
                        $i++;
                    }
                }
                $payment_Methods[] = $expedition;
            }

            $order->getPayment()->setAdditionalInformation('Payment_Methods', $payment_Methods);

            // INIT DATA TRANSACTION
            $orderId = $order->getEntityId();

            //$order->getPayment()->setCcStatusDescription('abo|payment custom');
            $order->setSendEmail(true);
            $order->setState("processing")->setStatus("processing");
            $order->save();
            // INSERT INTO  VARIABLE SESSION CHECKOUT replace last ORDER with this order
            $this->_checkoutSession->setLastOrderId($order->getId())
                ->setLastRealOrderId($order->getIncrementId())
                ->setLastOrderStatus($order->getStatus());


            // Send mail
            /*$emailSender = $this->_objectManager->create('\Magento\Sales\Model\Order\Email\Sender\OrderSender');
            $emailSender->send($order);*/


            $this->_checkoutSession->clearQuote()->clearStorage();
            $this->_checkoutSession->clearQuote();
            $this->_checkoutSession->clearStorage();
            $this->_checkoutSession->clearHelperData();
            $this->_checkoutSession->resetCheckout();
            $this->_checkoutSession->restoreQuote();

            $this->messageManager->addSuccess(__('Congratulation, success order'));
            $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            $result->setPath('checkout/onepage/success/');

            return $result;
            }
            }
            // ####################### COMMANDE SIMPLE PRODUIT   ########################
        }elseif(isset($post['type_order']) && $post['type_order'] == 'order'){

            //exit;
            if($post['cb']['cardNumber']!=''){
                $this->save_Cb($post['cb'],$customerId,$payment);
            }



            $quote->setPaymentMethod('securepayment');
            $payment->importData(['method' => 'paymentcustom']);
            $payment->save();
            $quote->save();

            // TRANSFORMATION QUOTE ==> ORDER
            $quoteManagement = $this->_objectManager->create('\Magento\Quote\Api\CartManagementInterface');
            $this->_checkoutSession
                ->setLastQuoteId($quote->getId())
                ->setLastSuccessQuoteId($quote->getId())
                ->clearHelperData();

            $order = $quoteManagement->submit($quote);
            $order->getPayment()->save();
            $order->save();

            if($post['iban']['prefixe']!=''){
                $this->save_Iban($post['iban'],$payment, $order->getId());
            }

            $i = 0;

            $order->getPayment()->setAdditionalInformation('type_order', $post['type_order']);
            $order->getPayment()->setAdditionalInformation('IBAN_prefixe', $post['iban']['prefixe']);
            $order->getPayment()->setAdditionalInformation('IBAN_etab', $post['iban']['etab']);
            $order->getPayment()->setAdditionalInformation('IBAN_guichet', $post['iban']['guichet']);
            $order->getPayment()->setAdditionalInformation('IBAN_compte', $post['iban']['compte']);
            $order->getPayment()->setAdditionalInformation('IBAN_cle', $post['iban']['cle']);

            $payment_Methods = array();
            $expedition = array();
            if(isset($post['method_payment']) && $post['method_payment']== 'cash'){
                $expedition[] =[
                    "Payment_Method" => $post['payment_mode'],
                    "Payment_Pct" => '100',
                    "Payed_Amount_Incl_VAT" => $post['total_price'],
                    "Due_Date" => date('m/d/Y'),
                    "Bank_Check_No" => $post['check']['number_check'],
                    "Bank_Name" => $post['check']['name_bank'],
                    "Bank_Check_Name" => $post['check']['owner_check']
                ];
            }elseif(isset($post['method_payment']) && $post['method_payment']== 'multi'){
                foreach ($post['form_multi'] as $value){
                    if($value['is_active']=='true'){
                        $expedition[] =[
                            "Payment_Method" => $value['mode_payment'],
                            "Payment_Pct" => $value['percentage'],
                            "Payed_Amount_Incl_VAT" => $value['amount'],
                            "Due_Date" => $value['date'],
                            "Bank_Check_No" => $value['check']['number_check'],
                            "Bank_Name" => $value['check']['name_bank'],
                            "Bank_Check_Name" => $value['check']['owner_check']
                        ];
                        $i++;
                    }else{
                        break;
                    }
                }
            }
            $payment_Methods[]=$expedition;

            $order->getPayment()->setAdditionalInformation('Payment_Methods', $payment_Methods);
            //$order->setSendEmail(true);
            $order->setState("processing")->setStatus("processing");
            $order->save();

            // INSERT INTO  VARIABLE SESSION CHECKOUT replace last ORDER with this order
            $this->_checkoutSession->setLastOrderId($order->getId())
                ->setLastRealOrderId($order->getIncrementId())
                ->setLastOrderStatus($order->getStatus());

            // Send mail
            /*$emailSender = $this->_objectManager->create('\Magento\Sales\Model\Order\Email\Sender\OrderSender');
            $emailSender->send($order);*/

            $this->_checkoutSession->clearQuote()->clearStorage();
            $this->_checkoutSession->clearQuote();
            $this->_checkoutSession->clearStorage();
            $this->_checkoutSession->clearHelperData();
            $this->_checkoutSession->resetCheckout();
            $this->_checkoutSession->restoreQuote();


            $this->messageManager->addSuccess(__('Congratulation, success order'));
            $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            $result->setPath('checkout/onepage/success/');

            return $result;
        }
    }
    public function deleteItemThisCard($quote,$itemId){
        $cart = $this->_objectManager->create('Magento\Checkout\Model\Cart');
        $nb=$cart->getQuote()->getAllItems();
        $cart->removeItem($itemId)->save();

    }

    public function verification($elem,$iban,$cb,$customerId){
        $verif=false;
        // FIRST ECHEANCE
        if(isset($elem['first_ech']) && count($elem['first_ech']) > 0 ){
            //var_dump($elem);
            // VERIFICATION DE LA COHERENCE du mode de paiement si ok
            if( !empty($elem['first_ech']['mode_payment']) && $elem['first_ech'][($elem['first_ech']['mode_payment'])] == 'ok' ){
                // VERIFICATION LOGIQUE SUR LE MODE DE PAIEMENT
                if($elem['first_ech']['mode_payment'] == 'IBAN' && count($iban)>0 ){
                    $verif=true;
                }elseif($elem['first_ech']['mode_payment'] == 'CB' && count($cb)>0 ){
                    $verif=true;
                }elseif($elem['first_ech']['mode_payment'] == 'CHEQUE' && count($elem['first_ech']['cheque'])>0
                    && $elem['first_ech']['cheque']['num_check']!=''
                    && $elem['first_ech']['cheque']['tireur']!=''
                    && $elem['first_ech']['cheque']['bankname']!=''){
                    $verif=true;
                }elseif($elem['first_ech']['mode_payment'] == 'ESPECE'){
                    $verif=true;
                }
            }
        }
        // IF OTHER ECHEANCE
        if($verif==true && isset($elem['other_ech']) && count($elem['other_ech']) > 0 && $elem['fraction']>1){
            $verif_other=false;
            // VERIFICATION DE LA COHERENCE du mode de paiement si ok ET SI UNE DATE DESIREE EXISTE
            if( !empty($elem['other_ech']['mode_payment']) && $elem['other_ech'][($elem['other_ech']['mode_payment'])] == 'ok'
                && isset($elem['other_ech']['date_desired']) && !empty($elem['other_ech']['date_desired'])){
                // VERIFICATION LOGIQUE SUR LE MODE DE PAIEMENT
                if($elem['other_ech']['mode_payment'] == 'IBAN' && count($iban)>0 ){
                    $verif_other=true;
                }elseif($elem['other_ech']['mode_payment'] == 'CB' && count($cb)>0 ){
                    $verif_other=true;
                }elseif($elem['other_ech']['mode_payment'] == 'CHEQUE' && count($elem['other_ech']['cheque'])>0){
                    for($i=2;$i<= intval($elem['fraction']) ;$i++){
                        if(    $elem['other_ech']['cheque'][$i]['num_check'] !=''
                            || $elem['other_ech']['cheque'][$i]['tireur']    !=''
                            || $elem['other_ech']['cheque'][$i]['bankname']  !=''){
                            $verif_other=true;
                        }
                    }
                    //var_dump($elem['other_ech']['cheque']);die();
                }elseif($elem['other_ech']['mode_payment'] == 'ESPECE'){
                    $verif_other=true;
                }
            }
            $verif=$verif_other;
        }

        return $verif;
    }
    public function saveAbo($quote,$abo,$num, $modePayment){
        $abo_product[] = ['product_id'=>$num,'qty'=>1, 'price'=>$abo['order_price'], 'mode'=>$modePayment];
        //var_dump($abo_product);die();

        // Set Sales Order Payment
        $quote->getPayment()->importData(['method' => 'paymentcustom']);

        /*var_dump($quote->getShippingAddress()->getData('shipping_method'));
        exit;*/
        $tempData =[
            'currency_id'  => $quote->getData('store_currency_code'),
            'customer_id'        => $quote->getData('customer_id'),
            'shipping_address'  =>[
                'firstname'     => $quote->getShippingAddress()->getData('firstname'), //address Details
                'lastname'      => $quote->getShippingAddress()->getData('lastname'),
                'street'        => $quote->getShippingAddress()->getData('street'),
                'city'          => $quote->getShippingAddress()->getData('city'),
                'country_id'    => $quote->getShippingAddress()->getData('country_id'),
                'region'        => $quote->getShippingAddress()->getData('region'),
                'postcode'      => $quote->getShippingAddress()->getData('postcode'),
                'telephone'     => $quote->getShippingAddress()->getData('telephone'),
                'fax'           => $quote->getShippingAddress()->getData('fax'),
                'shipping_method'=> $quote->getShippingAddress()->getData('shipping_method'),
                'save_in_address_book' => 0
            ],
            'items'=> $abo_product
        ];

        $result=$this->_objectManager->get('Ntic\PayCustom\Helper\Data')->createOrder($tempData);

        if(isset($result['order_id']) && $result['order_id']>0){
            return $result;
        }else{
            return false;
        }
    }
    public function saveDetailV2($order_id,$cmd,$item){
        $fraction = $cmd['fraction'];
        $datedesired = $cmd['other_ech']['date_desired'];
        $datedesiredformat00 = str_pad($datedesired, 2, 0, STR_PAD_LEFT);
        $result =array();
        for ($i = 1; $i <= $fraction; $i++){
            $payDetail = $this->_objectManager->create('Ntic\PayCustom\Model\DetailPayment');
            $payDetail->SetRefQuoteItem($item['item_id']);
            // first echeance
            if ($i == 1){
                $payDetail->setModePayment($cmd['first_ech']['mode_payment']);
                $payDetail->setFraction($fraction);
                $payDetail->setEcheance(1);
                $payDetail->setDateDesired(date("Y-m-d"));
                $modepayment = $payDetail->getmodePayment();
                if ($modepayment == 'ESPECE'){
                    $payDetail->setPaid(1);
                }elseif ($modepayment == 'CHEQUE'){
                    $payDetail->setPaid(1);
                    $payDetail->setRefCheck($checkFirstAbo);
                }elseif ($modepayment == 'IBAN'){
                    // A FAIRE : SCRIPT DE VERIF IBAN
                    $payDetail->setPaid(1);
                    $payDetail->setRefIban($ibanCreated);
                }

                $payDetail->save();
            }
            //$payDetail->SetRefQuoteItem($order_id);
        }
        return true;

    }

    public function save_Cb($cb,$customerId,$payment){

        $payment->setMethod('securepayment');
        $payment->setCcOwner($cb['owner']);
        $payment->setCcType($cb['cardType']);
        $payment->setCcNumberEnc($payment->encrypt($cb['cardNumber']));
        $payment->setCcExpMonth($cb['expiryMonth']);
        $payment->setCcExpYear($cb['expiryYear']);
        $payment->setCcCidEnc($payment->encrypt($cb['cvv']));

        if($payment->save()){
            $return = $payment->getId();
        }else{
            $return=false;
        }
        return $return;
    }

    private function save_Iban($iban, $payment, $orderId){
        $payment->setMethod('securepayment');
        $ibanConcat = $iban['prefixe'].$iban['etab'].$iban['guichet'].$iban['compte'].$iban['cle'];
        $ibanConcat = $payment->encrypt($ibanConcat);
        $query = "UPDATE sales_order SET iban='$ibanConcat' WHERE entity_id=$orderId";
        $this->pdo->exec($query);

        if($payment->save()){
            $return = $payment->getId();
        }else{
            $return=false;
        }
        return $return;
    }

    public function saveDetailPayment($order_id){
        $fraction = $_POST['abo'][$itemId]['fraction'];
        $datedesired = $_POST['abo'][$itemId]['other_ech']['date_desired'];
        $datedesiredformat = str_pad($datedesired, 2, 0, STR_PAD_LEFT);
        for ($i = 1; $i <= $fraction; $i++) {
            $payDetail = $objectManager->create('Ntic\PayCustom\Model\DetailPayment');
            $payDetail->SetRefQuoteItem($itemId);
            if ($i == 1) {
                $payDetail->setModePayment($_POST['abo'][$itemId]['first_ech']['mode_payment']);
                $payDetail->setFraction($fraction);
                $payDetail->setEcheance(1);
                $payDetail->setDateDesired(date("Y-m-d"));
                $modepayment = $payDetail->getmodePayment();
                if ($modepayment == 'ESPECE') {
                    $payDetail->setPaid(1);
                }
                if ($modepayment == 'CHEQUE') {
                    $payDetail->setPaid(1);
                    $payDetail->setRefCheck($checkFirstAbo);
                }
                if ($modepayment == 'IBAN') {
                    // A FAIRE : SCRIPT DE VERIF IBAN
                    $payDetail->setPaid(1);
                    $payDetail->setRefIban($ibanCreated);
                }
                $payDetail->setAmount($_POST['abo'][$itemId]['first_ech']['amount']);
            } else {
                $nextEchDate = ($i - 1);
                $payDetail->setModePayment($_POST['abo'][$itemId]['other_ech']['mode_payment']);
                $payDetail->setFraction($fraction);
                $payDetail->setEcheance($i);
                $payDetail->setDateDesired(date("Y-m-d", strtotime(date("Y-m-$datedesiredformat") . ' +' . $nextEchDate . ' month')));
                $payDetail->setAmount($_POST['abo'][$itemId]['other_ech']['amount']);
                $modepayment = $payDetail->getmodePayment();
                if ($modepayment == 'ESPECE') {
                    //$payDetail->setPaid(1);
                }
                if ($modepayment == 'CHEQUE') {
                    $payDetail->setRefCheck($checkMultiAbo[$i]);
                }
                if ($modepayment == 'IBAN') {
                    $payDetail->setRefIban($ibanCreated);
                }
                if ($modepayment == 'CB') {
                    $payDetail->setRefCreditcard($CBMultipleAbo);
                }


            }
            /*var_dump($payDetail); */
            $payDetail->save();
        }
    }


    public function cancelOrderWithResponse2($order,$responseText){
        $order->setState("canceled")->setStatus($order::STATE_CANCELED);
        $order->save();
        // RELOAD QUOTE INTO CHECKOUT
        $this->_checkoutSession->setLastQuoteId($order->getQuoteId())->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId())
            ->setLastOrderStatus($order->getStatus());
        $this->_checkoutSession->restoreQuote();

        if($responseText == ""){
            $this->messageManager->addError( __('Error, fail order') );
        }else{
            $this->messageManager->addError($responseText);
        }
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        //return $result->setPath('custompayment');
        return $result->setPath('checkout/cart/');
    }
    private function getLibError($code){
        /*
         * TRANSLATE CODE ERROR
         */
        $translate_results = array(
            0 => 'transaction approuvée ou traitée avec succès',
            2 => 'contacter l’émetteur de carte',
            3 => 'accepteur invalide',
            4 => 'conserver la carte',
            5 => 'ne pas honorer',
            7 => 'conserver la carte, conditions spéciales',
            8 => 'approuver après identification',
            12 => 'transaction invalide',
            13 => 'montant invalide',
            14 => 'numéro de porteur invalide',
            30 => 'erreur de format',
            31 => 'identifiant de l’organisme acquéreur inconnu',
            33 => 'date de validité de la carte dépassée',
            34 => 'suspicion de fraude',
            41 => 'carte perdue',
            43 => 'carte volée',
            51 => 'provision insuffisante ou crédit dépassé',
            54 => 'date de validité de la carte dépassée',
            56 => 'carte absente du fichier',
            57 => 'transaction non permise à ce porteur',
            58 => 'transaction interdite au terminal',
            59 => 'suspicion de fraude',
            60 => 'l’accepteur de carte doit contacter l’acquéreur',
            61 => 'montant de retrait hors limite',
            63 => 'règles de sécurité non respectées',
            68 => 'réponse non parvenue ou reçue trop tard',
            90 => 'arrêt momentané du système',
            91 => 'émetteur de cartes inaccessible',
            96 => 'mauvais fonctionnement du système',
            94 => 'transaction dupliquée',
            97 => 'échéance de la temporisation de surveillance globale',
            98 => 'serveur indisponible routage réseau demandé à nouveau',
            99 => 'incident domaine initiateur',
            100 => 'information introuvable');
        return $translate_results[$code];
    }


    private function changeGroupCustomer($sku){
        $item = $this->productRepositoryInterface->get($sku);

        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();

        $product =$objectManager->create('Magento\Catalog\Model\Product')->load($item->getId());
        $categories = $product->getCategoryIds();
        $catName = '';
        $groupeName = '';
        foreach($categories as $category){
            $cat = $objectManager->create('Magento\Catalog\Model\Category')->load($category);
            $catName = $cat->getName();

            if($catName == 'LBOX'){
                $groupeName = 'LXL30';

            }else if($catName == 'Carte Club' || $sku == 'A001'){

                $groupeName = 'LXL25';
            }else if($catName == 'Carte Club' || $sku == 'A005'){
  
                $groupeName = 'LXL50';
            }
        }
        $customerGroupsCollection = $objectManager->get('\Magento\Customer\Model\ResourceModel\Group\Collection');
        $customerGroups = $customerGroupsCollection->toOptionArray();
        //var_dump($customerGroups);
        $groupid='';
        foreach ($customerGroups as $value){
            if($value['label']==$groupeName){
                $groupid = $value['value'];
            }
        }
        $idCustomer = $this->customerSession->getCustomer()->getId();
        $query = "UPDATE customer_entity SET group_id=$groupid WHERE entity_id=$idCustomer";
        $this->pdo->exec($query);
        //$this->customerSession->getCustomer()->setGroupId($groupid)->save();

        return true;
    }

    private function saveOrder($order, $product){

        $order
            ->setShippingMethod('owsh1_free_method')
            ->setShippingDescription('Livraison Standard - Livraison Gratuite');

// set totals
        $order->setBaseGrandTotal($product->getPrice());
        $order->setGrandTotal($product->getPrice());
        $order->setBaseSubtotal($product->getPrice());
        $order->setSubtotal($product->getPrice());
        $order->setBaseTaxAmount(0);
        $order->setBaseSubtotalInclTax($product->getPrice());
        $order->setSubtotalInclTax($product->getPrice());
        $order->setTotalItemCount(1);
        $order->setTotalQtyOrdered(1);

// set shipping amounts
        $order->setShippingTaxAmount(0);
        $order->setBaseShippingTaxAmount(0);
        $order->setShippingInclTax($product->getPrice());
        $order->setBaseShippingInclTax($product->getPrice());

        $order->getPayment()->setCcStatusDescription('abo|payment custom');
        $order->setSendEmail(true);
        $order->setState("processing")->setStatus("processing");
        $order->save();
    }

    private function saveIbanCustomer($numCustomer, $iban){

    }


}