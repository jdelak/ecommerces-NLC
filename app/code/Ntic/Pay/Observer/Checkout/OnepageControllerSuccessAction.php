<?php


namespace Ntic\Pay\Observer\Checkout;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class OnepageControllerSuccessAction implements \Magento\Framework\Event\ObserverInterface
{
    protected $_responseFactory;
    protected $_url;

    public function __construct(

        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
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
        $event = $observer->getEvent();
        $RedirectUrl= $this->_url->getUrl('securepayment');
        $this->_responseFactory->create()->setRedirect($RedirectUrl)->sendResponse();
    }
}
