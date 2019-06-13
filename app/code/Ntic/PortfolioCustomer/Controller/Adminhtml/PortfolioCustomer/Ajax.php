<?php


namespace Ntic\PortfolioCustomer\Controller\Adminhtml\PortfolioCustomer;
use Ntic\Common\Helper\Datatable\Datatable;
use Ntic\Common\Helper\Datatable\SSP;
use Ntic\Common\Helper\Datatable\SHELPER;


class Ajax extends \Magento\Backend\App\Action
{

    protected $resultFactory;
    protected $jsonHelper;
    private $params;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct( \Magento\Backend\App\Action\Context $context ) {
        parent::__construct($context);
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->resource = $this->objectManager->get('Magento\Framework\App\ResourceConnection');

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
        switch ($this->params['action']){
            case 'list_all_customers':
                $listOfCustomer = new Datatable($this->params);
                $listOfCustomer->get('customer_entity', 'entity_id', array('entity_id', 'firstname','lastname','email'));
                break;
            case 'list_attached_customers':
                $tableNameTable1 = $this->_db->getTableName('ntic_portfolio_customer');
                $tableCustomer = $this->_db->getTableName('customer_entity');
                $columns = array(
                    array( 'db' => 'entity_id', 'dt' => 0, 'field' => 'entity_id', 'as' => 'entity_id' ),
                    array( 'db' => 'firstname', 'dt' => 1, 'field' => 'firstname', 'as' => 'firstname' ),
                    array( 'db' => 'lastname',  'dt' => 2, 'field' => 'lastname',  'as' => 'lastname' ),
                    array( 'db' => 'email',     'dt' => 3, 'field' => 'email',     'as' => 'email' ),
                );
                echo json_encode(
                    SSP::complex (
                        $this->params,
                        $this->_db,
                        $tableCustomer,
                        'entity_id',
                        $columns,
                        null,
                        'entity_id IN (SELECT DISTINCT(customer_id) FROM '.$tableNameTable1.' WHERE ntic_portfolio_customer.seller_id = '. $this->params['seller_id'] . ' )' )
                );
                break;
            case 'list_free_customers':
                $tableNameTable1 = $this->_db->getTableName('ntic_portfolio_customer');
                $tableCustomer = $this->_db->getTableName('customer_entity');
                $columns= array(
                    array( 'db' => 'entity_id', 'dt' => 0, 'field' => 'entity_id', 'as' => 'entity_id' ),
                    array( 'db' => 'firstname', 'dt' => 1, 'field' => 'firstname', 'as' => 'firstname' ),
                    array( 'db' => 'lastname',  'dt' => 2, 'field' => 'lastname',  'as' => 'lastname'),
                    array( 'db' => 'email',     'dt' => 3, 'field' => 'email',     'as' => 'email'),
                );

                echo json_encode(
                    SHELPER::simple (
                        $this->params,
                        $this->_db,
                        $tableCustomer,
                        'entity_id',
                        $columns,
                        'FROM customer_entity LEFT OUTER JOIN ntic_portfolio_customer ON customer_entity.entity_id = ntic_portfolio_customer.customer_id ',
                        'customer_entity.group_id != 4 AND customer_entity.group_id != 5',
                        'customer_entity.entity_id',
                        'COUNT(ntic_portfolio_customer.customer_id) =0'
                    )
                );
                break;
            case 'dislink':
                $this->dislink($this->params['customer_id'], $this->params['seller_id']);
                break;
            case 'link':
                $this->attach($this->params['customer_id'], $this->params['seller_id']);
                break;
            default:

        }
    }

    public function dislink($to_dislink,$seller){
        /* INITIALISATION */
        $portfolio = $this->_objectManager->create('Ntic\PortfolioCustomer\Model\PortfolioCustomer');
        $to_dislink = $portfolio->getCollection()->addFieldToFilter('seller_id', $seller)->addFieldToFilter('customer_id', $to_dislink)->getFirstItem()->getData('portfolio_customer_id');
        $portfolio->getPortfolioCustomerId($to_dislink);
        $portfolio->load($to_dislink)->get();
        $portfolio->delete();

    }

    public function attach($the_attach,$seller) {
        /* INITIALISATION */
        $portfolio = $this->_objectManager->create('Ntic\PortfolioCustomer\Model\PortfolioCustomer');
        $portfolio->setSellerId($seller);
        $portfolio->setCustomerId($the_attach);
        $portfolio->save();
    }
}
