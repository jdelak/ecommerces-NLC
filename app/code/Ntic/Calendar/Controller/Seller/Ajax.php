<?php
namespace Ntic\Calendar\Controller\Seller;


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


    public function execute()
    {
        $tableCustomer = $this->resource->getTableName('customer_entity');

        //var_dump($objectManager);
        print("<?xml version=\"1.0\"?>");
        $sql = "SELECT customer_entity.entity_id, 
                        customer_entity.group_id ,
                        customer_entity.firstname, 
                        customer_entity.lastname,email, 
                        customer_entity_varchar.value,
                        customer_entity_varchar.attribute_id
                        FROM " . $tableCustomer . " 
                        LEFT JOIN  
                        customer_entity_varchar ON customer_entity_varchar.entity_id = ".$tableCustomer.".entity_id
                        WHERE 
                        (
                            customer_entity.entity_id LIKE '" . $_GET["mask"] . "%' 
                            OR customer_entity_varchar.value LIKE '%" . $_GET["mask"] . "%' 
                            OR customer_entity.firstname LIKE '" . $_GET["mask"] . "%' 
                            OR customer_entity.lastname LIKE '" . $_GET["mask"] . "%'
                        ) 
                        AND (
                             customer_entity.group_id = 4 OR customer_entity.group_id = 5
                        ) 
                        AND customer_entity_varchar.attribute_id = 143 ";

        print("<complete add='true'>");
        $res = $this->_db->query($sql)->fetchAll();

        if ($res) {
            foreach ($res as $row) {
                print("<option value=\"" . $row["entity_id"] . "\">");
                print($row["lastname"] . ' ' . $row["firstname"]. ' [' . $row['value']. '] ');
                print("</option>");
            }
        } else {

        }
        print("</complete>");
    }
}

?>
