<?php
namespace Ntic\Calendar\Controller\ClientDetails;

use Magento\Framework\App\Bootstrap;
use Ntic\Calendar\Helper\dhtmlxScheduler\common\connector\db_connector;

class Ajax extends \Magento\Framework\App\Action\Action {
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

    public function execute() {
        $tableCustomer = $this->resource->getTableName('customer_entity');

        if (isset($_GET['client_id'])) {
            $sql = " SELECT 
                    customer.entity_id as id,
                    customer.prefix,
                    customer.email,
                    customer.firstname,
                    customer.lastname,
                    address.city,
                    address.postcode as zip_code,
                    address.country_id as country,
                    address.telephone as tel1,
                    address.street as addr1
                     FROM ".$tableCustomer." as customer
                     LEFT JOIN customer_address_entity as address ON customer.entity_id = address.parent_id
                     WHERE customer.entity_id = ".$_GET['client_id'];

            $result = $this->_db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
            echo json_encode($result);
        }
    }
}
?>
