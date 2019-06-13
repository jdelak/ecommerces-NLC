<?php

namespace Ntic\Subscription\Observer\Sales;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager as _objectManager;

class OrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Request instance
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    protected $_orderFactory;
    protected $_checkoutSession;
    protected $_logger;
    protected $_resource;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ){
        $this->request = $context->getRequestInterface();
        $this->_orderFactory = $orderFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->_logger = $context->getLoggerInterface();
        $this->_resource = $resource;
        parent::__construct($context);
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /**
         * Instanciate
         */
        $connection  = $this->_resource->getConnection();
        $objectManager = _objectManager::getInstance(); // Instance de ObjectManager
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');  // Instance de StoreManager
        /**
         * Get Data
         */
        $tableName   = $connection->getTableName('ntic_subscription_contract'); // Table ou l'on va inserer
        $postData = $this->request->getPost(); // Récupère les data de la request --> use RequestInterface
        $storeId = $storeManager->getStore()->getId();  // Recupère le Store Id --> use StoreManager
        $order = $observer->getEvent()->getOrder(); // Objet relatif a la commande lors de la validation de la commande --> use ObserverEvent
        $orderId = $order->getIncrementId(); // Id de l'order la validation de la commande --> use ObserverEvent
        /**
         * Treatment
         */


//        $this->_logger->addDebug('Collection__ ');
//        $options = ['test'=> 'ok'];
//        $this->_logger->log(100,"Collection= " .print_r($options));
         /*var_dump($postData);
        exit;*/
        if (isset($postData['type_order']) && $postData['type_order'] == "subscription") {
            $subscription_date = $postData['form_multi'][0]['date'];
            $month = substr($subscription_date, 3, 2);
            $day = substr($subscription_date, 0, 2);
            /**
             * Set Contract Entity Attributes
             */
            $model = $objectManager->create('Ntic\Subscription\Model\Contract');
            $model->setStoreId($storeId);
            $model->setOrderId($orderId);
            $model->setDateSuscription($subscription_date);
            $model->setMonthSubscription($month);
            $model->setDaySubscription($day);
            $model->setHonnored((bool) true);
            $model->setIndice((int) 1);
            $model->setStatus((bool) true);

            /**
             * Récupérer les données de NAVISION
             * Continuer de Set les attributs de Contract
             * Puis Save les Data
             */

           // Sauvegarde les données dans la table $model->save();
//            echo 'MODEL';
//            var_dump($model);
//            exit();
        }



//        foreach($order->getAllItems() as $item){
//            echo 'prod_id';
//            var_dump($item->getProductId());
//            echo 'prod_name';
//            var_dump($item->getName()); // product name
//            echo 'prod_sku';
//            var_dump($item->getProduct()->getSku());
//        }

    }
}