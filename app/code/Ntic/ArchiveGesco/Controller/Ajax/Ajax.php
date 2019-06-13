<?php

namespace Ntic\ArchiveGesco\Controller\Ajax;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Ntic\Common\Helper\Datatable\Datatable;
use Ntic\Common\Helper\Datatable\SSP;
use Ntic\Common\Helper\Datatable\SHELPER;

use Ntic\ArchiveGesco\Block;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Ajax extends \Magento\Framework\App\Action\Action {
    protected $resultJsonFactory;
    private $params;

    /**
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory){
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);

        // GET
        $this->params = $this->getRequest()->getParams();
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $layout = $this->_view->getLayout();
        $block = $layout->createBlock('Ntic\ArchiveGesco\Block\Gesco');

        $row = array(
            array( 'db' => 'gclcode', 'dt' => 0, 'field' => 'id',         'as' => 'id' ),
            array( 'db' => 'gclnom',  'dt' => 1, 'field' => 'lastname',   'as' => 'lastname' ),
            array( 'db' => 'gclpren', 'dt' => 2, 'field' => 'firstname',  'as' => 'firstname' ),
            array( 'db' => 'gcldatn', 'dt' => 3, 'field' => 'birthday',   'as' => 'birthday' ),
            array( 'db' => 'gclddcd', 'dt' => 4, 'field' => 'last_order', 'as' => 'last_order' ),
        );

        echo json_encode(
            SHELPER::simple (
                $this->params,
                $block->getConnectionDatabase(),
                'gcliact',
                'gclcode',
                $row,
                'FROM gcliact'
            )
        );
    }
}
