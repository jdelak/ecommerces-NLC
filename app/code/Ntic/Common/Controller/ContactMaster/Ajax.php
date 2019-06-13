<?php

namespace Ntic\Common\Controller\ContactMaster;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

use Ntic\Common\Block;


class Ajax extends \Magento\Framework\App\Action\Action {
    protected $resultJsonFactory;

    /**
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory){
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $requestGet = $this->getRequest()->getParams('customer_id');

        $resultJson = $this->resultJsonFactory->create();

        // Requete GET pour les linsting RDV, LANDING, PARRAIN
        if(isset($_GET['resource'])) {
            $layout = $this->_view->getLayout();
            $block = $layout->createBlock('Ntic\Common\Block\ContactMaster');

            // PARRAIN
            if($_GET['resource'] == 'godfather') {
                return $resultJson->setData(['success' => $block->listAjaxGodfather($_GET['search'])]);
            }

            // LANDING
            if($_GET['resource'] == 'landing') {
                return $resultJson->setData(['success' => $block->listLead()]);
            }

            // RDV
            if($_GET['resource'] == 'calendar') {
                return $resultJson->setData(['success' => $block->listAjaxCalendar($_GET['seller_id'])]);
            }
        }

        if(isset($requestGet['customer_id'])) {
            $layout = $this->_view->getLayout();
            $block = $layout->createBlock('Ntic\Common\Block\ContactMaster');

            return $resultJson->setData(['success' => $block->getUser((int) $requestGet['customer_id'])]);
        } else {
            return $resultJson->setData(['success' => 'false']);
        }

        return $resultJson->setData(['success' => 'true']);
    }
}
