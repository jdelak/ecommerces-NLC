<?php
namespace Ntic\PortfolioCustomer\Controller\Index;
use Ntic\Common\Helper\Datatable\Datatable;
use Ntic\Common\Helper\Datatable\SSP;
use Ntic\Common\Helper\Datatable\SHELPER;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Ajax extends \Magento\Framework\App\Action\Action

{
    protected $resultFactory;
    protected $jsonHelper;
    private $params;
    private $customerSession;
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct( \Magento\Framework\App\Action\Context $context ) {
        parent::__construct($context);
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->resource = $this->objectManager->get('Magento\Framework\App\ResourceConnection');
        $this->customerSession = $this->objectManager->create('\Magento\Customer\Model\Session');

        $this->_db = $this->resource->getConnection();
        $this->params = $this->getRequest()->getParams();
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $tableNameTable1 = $this->_db->getTableName('ntic_portfolio_customer');
        $tableCustomer = $this->_db->getTableName('customer_entity');
        $columns = array(
            array( 'db' => 'p.customer_id',  'dt' => 0, 'field' => 'p.customer_id', 'as' => 'customer_id' ),
            array( 'db' => 'p.seller_id',    'dt' => '', 'field' => 'p.seller_id',   'as' => 'seller_id' ),
            array( 'db' => 'c.firstname',    'dt' => 2, 'field' => 'c.firstname',   'as' => 'firstname' ),
            array( 'db' => 'c.lastname',     'dt' => 3, 'field' => 'c.lastname',    'as' => 'lastname' ),
            array( 'db' => 'c.email',        'dt' => 4, 'field' => 'c.email',       'as' => 'email' ),
            array( 'db' => 'v.value',        'dt' => 1, 'field' => 'v.value',       'as' => 'value' ),
            array( 'db' => 'v.attribute_id', 'dt' => '', 'field' => 'v.attribute_id','as' => 'attribute_id' ),

        );

        echo json_encode(
            SHELPER::simple (
                $this->params,
                $this->_db,
                $tableNameTable1,
                'seller_id',
                $columns,
                'FROM ntic_portfolio_customer p
                 LEFT JOIN customer_entity c ON c.entity_id = p.customer_id 
                 LEFT JOIN customer_entity_varchar v ON v.entity_id = c.entity_id 
                ',
                'p.seller_id = '. $this->customerSession->getId() .' AND v.attribute_id = 141',
                'p.customer_id',
                ''
            )
        );

    }

}