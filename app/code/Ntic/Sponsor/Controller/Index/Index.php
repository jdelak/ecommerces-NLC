<?php


namespace Ntic\Sponsor\Controller\Index;
use Ntic\Common\Helper\HandlerContactMaster;
use Ntic\Common\Block\ContactMaster;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $contactMaster;
    protected $customerSession;
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Element\Template\Context $contextView,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->contactMaster = new ContactMaster($contextView);
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->setGiftGodfather();
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }

    private function setGiftGodfather(){

        $dateD = date('Y-m-d').' 00:00:00';
        $dateF = date('Y-m-d').' 23:59:59';

        var_dump($this->customerSession->getCustomer()->getData());
        exit;
        $id_customer = $this->customerSession->getCustomer()->getId();
        $id_contact_main = $this->contactMaster->getContactByExtID($id_customer)['contact_id'];
        $query = "SELECT id FROM contact_sponsor WHERE created_at >= '$dateD' AND created_at <= '$dateF' AND main_contact_sponsor = 4231";
        $nbGodfather = $this->contactMaster->getCount($query);
        if($nbGodfather >= 5 && $nbGodfather < 10){
            $_SESSION['gift_godfather'] = 1;
        }elseif($nbGodfather >= 10){
            $_SESSION['gift_godfather'] = 2;
        }else{
            $_SESSION['gift_godfather'] = 0;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');

        $items = $cart->getQuote()->getAllItems();
        foreach($items as $item) {
            $product =$objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());
            $categories = $product->getCategoryIds();
            foreach($categories as $category){
                $cat = $objectManager->create('Magento\Catalog\Model\Category')->load($category);
                if($cat->getId() == 58 || $cat->getId() == 59){
                    $_SESSION['gift_godfather'] = 0;
                }
            }
        }
        return true;
    }

}
