<?php
namespace Ntic\Pay\Controller\Index;
use Magento\Framework\App\ObjectManager;
use Ntic\Pay\Helper\Payzen\SoapV5;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Controller\ResultFactory;

class Submit extends \Magento\Framework\App\Action\Action
{
    /*
     * Permet d'executer le script simple pour un paiment simple sur PAYZEN
     */
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $checkoutSession;
    protected $resultRedirectFactory;
    protected $cartManagement;
    protected $_storeManager;
    protected $_objectManager;
    protected $certifCollection;
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
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
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Ntic\Pay\Model\ResourceModel\Certif\Collection $certifCollection
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->jsonHelper           = $jsonHelper;
        $this->_checkoutSession     = $checkoutSession;
        $this->resultFactory        = $context->getResultFactory();
        $this->_storeManager        = $storeManager;
        $this->_objectManager       = $context->getObjectManagerInterface();
        $this->certifCollection     = $certifCollection;
        parent::__construct($context);
    }

    public function execute()
    {
        // pour debuger et exit le code + afficher la response PAYZEN
        $debug = false;
        
        // INITIALISATION
        $text_response='';
        $store = $this->_storeManager->getStore();
        $quote = $this->_checkoutSession->getQuote();
        $payment = $quote->getPayment();

        // INIT DATA FOR PAYMENT
        $payment->setMethod('securepayment');
        $payment->setCcOwner($_POST['owner']);
        $payment->setCcType($_POST['cardType']);
        $payment->setCcNumberEnc($payment->encrypt($_POST['cardNumber']));
        $payment->setCcExpMonth($_POST['expiryMonth']);
        $payment->setCcExpYear($_POST['expiryYear']);
        $payment->setCcCidEnc($payment->encrypt($_POST['cvv']));

        $payment->save();
        $quote->save();

        // TRANSFORMATION QUOTE ==> ORDER
        $quoteManagement = $this->_objectManager->create('\Magento\Quote\Api\CartManagementInterface');
        $this->_checkoutSession
            ->setLastQuoteId($quote->getId())
            ->setLastSuccessQuoteId($quote->getId())
            ->clearHelperData();
        $order = $quoteManagement->submit($quote);

        // INIT DATA TRANSACTION
        $orderId    = $order->getEntityId();
        $grandtotal = $order->getGrandTotal();
        $amount     = $grandtotal * 100;
        $currency   = $store->getCurrentCurrencyCode();
        $codeCur    = ($currency == 'EUR')?978:''; // SPECIFIQUE PAYZEN
        $scheme     = $this->getScheme($_POST['cardType']);

        try {
            // CHARGE SOAP PAYZEN WS V5
            $v5 = new SoapV5($this->getConfig(),array() );
            //sleep(1);
            $args = array(
                'amount'            => $amount,                                     // The amount of the transaction presented in the smallest unit of the currency (cents for Euro).
                'currency'          => $codeCur,                                    // An ISO 4217 numerical code of the payment currency.
                'cardNumber'        => str_replace(' ','',$_POST['cardNumber']),    // customer payment or credit card number
                'expiryMonth'       => $_POST['expiryMonth'],                       // customer card expiry month
                'expiryYear'        => $_POST['expiryYear'],                        // customer card expiry year
                'csc'               => $_POST['cvv'],                               // customer card CSC
                'scheme'            => $scheme,                                     // customer card scheme
                'orderId'           => $orderId,                                    // your order identifier
                'insuranceAmount'   => ''
            );
            // Detail réponse PAYZEN
            $response           = $v5->simpleCreatePayment($args);
            $commonResponse     = $response->createPaymentResult->commonResponse;
            $paymentResponse    = $response->createPaymentResult->paymentResponse;
            //sleep(1);
            if( isset($commonResponse->transactionStatusLabel) && isset($paymentResponse->transactionUuid)){
                // ------- BEGIN DEBUG-------
                if($debug == TRUE){
                    echo "<pre>Response code: {$commonResponse->responseCode}\n";
                    echo "Payment status: {$commonResponse->transactionStatusLabel}\n";
                    print_r($response);
                    echo "</pre>";
                    exit();
                }
                // ------- END DEBUG -------
                $order->getPayment()->save();
                $order->save();

                // Ajoute uuid dans la bdd
                $uuid = $paymentResponse->transactionUuid;
                $order->getPayment()->setAdditionalInformation($uuid);

                // type de commande pour NAV (par defaut sur le site il sagit d'une order)
                $order->getPayment()->setAdditionalInformation('type_order', 'order');

                $amountNAV = ($amount/100);
                $payment_Methods = array();
                $expedition = array();
                $expedition[] =[
                    "Payment_Method" => 'cb',
                    "Payment_Pct" => '100',
                    "Payed_Amount_Incl_VAT" => $amountNAV,
                    "Due_Date" => date('m/d/Y'),
                    "Bank_Check_No" => '',
                    "Bank_Name" => '',
                    "Bank_Check_Name" => ''
                ];
                $payment_Methods[]=$expedition;

                $order->getPayment()->setAdditionalInformation('Payment_Methods', $payment_Methods);


                if($commonResponse->transactionStatusLabel === "AUTHORISED"){
                    // SI pour PAYZEN tout est OK //
                    $order->getPayment()->setCcStatusDescription($commonResponse->transactionStatusLabel);
                    $order->setSendEmail(true);
                    $order->setState("processing")->setStatus("processing");
                    $order->save();
                    // INSERT INTO  VARIABLE SESSION CHECKOUT replace last ORDER with this order
                    $this->_checkoutSession->setLastOrderId($order->getId())
                         ->setLastRealOrderId($order->getIncrementId())
                         ->setLastOrderStatus($order->getStatus());
                    // Send mail
                    $emailSender = $this->_objectManager->create('\Magento\Sales\Model\Order\Email\Sender\OrderSender');
                    $emailSender->send($order);

                    // Success
                    $this->messageManager->addSuccess( __('Congratulation, success order') );
                    $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
                    $result->setPath('checkout/onepage/success/');
                }else{
                    // ERROR DIRECT IN PAYMENTRESPONSE[paymentError]
                    if(isset($paymentResponse->paymentError) && $paymentResponse->paymentError != ''){
                        $text_response = $commonResponse->transactionStatusLabel.' ('.$paymentResponse->paymentError.')';
                        $order->getPayment()->setCcStatusDescription($text_response . '[paymentresponse]');
                    // ERROR PLUS PRECISE on doit rechercher le code erreur [authorizationResponse] on doit avoir le uuid
                    }elseif(isset($paymentResponse->transactionUuid)){
                        //sleep(1);
                        $responseDetail         = $v5->detailsPayment($uuid);
                        $payzenDetailsResult    = $responseDetail->getPaymentDetailsResult->authorizationResponse->result;
                        $code_error             = (isset($payzenDetailsResult)) ? $payzenDetailsResult : 100;
                        $text_response          = $commonResponse->transactionStatusLabel . ' : ' . __($this->getLibError($code_error));
                        $order->getPayment()->setCcStatusDescription($text_response);
                    }
                    $result = $this->cancelOrderWithResponse2($order,$text_response);
                }
            }else{
                $result = $this->cancelOrderWithResponse2($order,$text_response);
            }

        } catch (Exception $e) {
            echo '<pre>';
            echo "\n ### ERROR - Something's wrong, an exception raised during process:\n";
            echo $e;
            echo '</pre>';
            $result = false;
        }
        return $result;
    }

    public function cancelOrderWithResponse2($order,$responseText){
        // Permet d'annuler la commande en cours
        // Permet la redirection vers la page de paiement avec un message d'erreur
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
        return $result->setPath('securepayment');
    }

    public function getConfig(){
        // RECUPERATION dans la table certif les certificats correspondant à la store view courante

        $storeId = $this->_storeManager->getStore()->getStoreId();
        $certif = $this->_objectManager->create('\Ntic\Pay\Model\Certif')->getCollection()->addFieldToFilter('store', $storeId)->getFirstItem();
        $shopId = $certif->getShopId();
        $certTest = $certif->getCertTest();
        $certProd = $certif->getCertProd();
        $ctxMode = $certif->getCtxMode();
        $wsdl = $certif->getWsdl();
        $ns = $certif->getNs();
        $my_config = array(
            'shopID'        => $shopId,     // shopId
            'certTest'      => $certTest,   // certificate, TEST-version
            'certProd'      => $certProd,   // certificate, PRODUCTION-version
            'ctxMode'       => $ctxMode,    // PRODUCTION || TEST
            'wsdl'          => $wsdl,       // URL of PayZen SOAP V5 WSDL
            'ns'            => $ns,         // ns,
        );
        return $my_config;
    }

    public function getScheme($cardType){
        switch ($cardType) {
            case 'AE':
                $scheme = 'AMEX';
                break;
            case 'VI':
                $scheme = 'VISA';
                break;
            case 'MC':
                $scheme = 'MASTERCARD';
                break;
            case 'DI':
                $scheme = 'CB';
                break;
            case 'CB':
                $scheme = 'CB';
                break;
            default:
                $scheme = 'CB';
        }
        return $scheme;
    }

    private function getLibError($code){
        /*
         * TRANSLATE CODE ERROR
         */
        $tranlate_results = array(
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
        return $tranlate_results[$code];
    }
}
?>