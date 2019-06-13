<?php


namespace Ntic\Common\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RedirectCart implements \Magento\Framework\Event\ObserverInterface
{
    protected $_responseFactory;
    protected $_url;
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */

    public function __construct(

        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
    }

    public function execute(
        \Magento\Framework\Event\Observer $observer
    )
    {
        // INIT

        $request = $observer->getRequest();
        $product = $observer->getProduct();
        $sku=$product->getSku();
        if($sku == 'DOTA'){

            $resultRedirect = $this->_responseFactory->create();
            $resultRedirect->setRedirect($this->_url->getUrl('checkout'))->sendResponse('200');
        }

    }

}
