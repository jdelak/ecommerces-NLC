<?php
namespace Ntic\Calendar\Controller\ContactDetails;

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
        
        $res = new \PDO("mysql:host=localhost;dbname=contact_master_test;charset=UTF8", "root", "Kay8lWiBMQwu");
        $dbtype = "PDO";

        if (isset($_GET['contact_id'])) {
            $sql = "SELECT 	
            contact.id,
            contact.lastname,
            contact.firstname,
            contact.email,
            contact.tel1,
            contact.tel2,
            contact.tel3,
            contact.prefix,
            contact.birthday,
            contact.updated_at,
            contact.created_at,
            contact.exploited_at, 
            contact_address.addr1,
            contact_address.addr2,
            contact_address.addr3,
            contact_address.zip_code,
            contact_address.country,
            contact_address.city,
            contact_address.created_at       
            FROM contact 
            LEFT JOIN contact_address ON contact.id = contact_address.contact_id 
            WHERE contact.id = " . $_GET['contact_id'];
            $result = $res->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
            echo json_encode($result);

        }
    }
}
?>
