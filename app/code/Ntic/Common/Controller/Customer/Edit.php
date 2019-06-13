<?php
namespace Ntic\Common\Controller\Customer;

use Magento\Framework\App\ObjectManager;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Edit extends \Magento\Framework\App\Action\Action {

    protected $resultFactory;
    protected $jsonHelper;
    private $params;

    public function __construct(
        \Ntic\PortfolioCustomer\Model\PortfolioCustomer $portfolioCustomer,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Backend\App\Action\Context $context
    ){
        parent::__construct($context);
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->portfolioCustomer = $portfolioCustomer;
        $this->_orderCollectionFactory = $orderCollectionFactory;


    }

    public function execute(){

        $cusRepo = $this->objectManager->create('Magento\Customer\Api\CustomerRepositoryInterface');
        $addressRepo = $this->objectManager->create('Magento\Customer\Api\AddressRepositoryInterface');


        if(isset($_POST)) {
            $id = $_POST['customer'];
            $customer = $cusRepo->getById(intval($id));
            $orders = $this->_orderCollectionFactory->create()
                ->addFieldToSelect('*')->addFieldToFilter('customer_id', $customer->getId())
                ->setOrder('created_at', 'desc');
            $addresses = $customer->getAddresses();
            return $orders;
        }



    }
}