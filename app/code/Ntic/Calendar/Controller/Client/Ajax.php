<?php
namespace Ntic\Calendar\Controller\Client;


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
        $customerSession = $this->objectManager->get('Magento\Customer\Model\Session');
        // Si le manager est connecté et a choisis un vendeur alors $customer_id aura la valeur du parametre $_GET
        if (isset($_GET['manager']) && $_GET['manager'] == true ) {
            if (isset($_GET['user_id'])) {
                $customer_id = $_GET['user_id'];
            }
        } else {
            $customer_id = $customerSession->getData('customer_id');
        }

        $connection = $this->resource->getConnection();
        $tableCustomer = $this->resource->getTableName('customer_entity');

        print("<?xml version=\"1.0\"?>");
        /**
         * ****************************************************************************************************
         *     REQUETE POUR AVOIR LES CLIENTS RATTACHE A LA VENDEUSE CONNECTEE OU SELECTIONNE PAR LE MANAGER
         * ****************************************************************************************************
         */
        $sql = "SELECT 
                        customer_entity.entity_id,
                        customer_entity.firstname, 
                        customer_entity.lastname,
                        email
                        FROM " . $tableCustomer . " 
                        LEFT JOIN  
                        ntic_portfolio_customer ON ntic_portfolio_customer.customer_id = ".$tableCustomer.".entity_id
                        WHERE ntic_portfolio_customer.seller_id = ". $customer_id ." 
                        AND (
                              LOWER(customer_entity.entity_id) LIKE LOWER('" . $_GET["mask"] . "%') 
                                OR   LOWER(customer_entity.firstname) LIKE LOWER('" . $_GET["mask"] . "%') 
                                OR   LOWER(customer_entity.lastname) LIKE LOWER('" . $_GET["mask"] . "%')
                            )";
        /**
         * *******************************************************************************
         */
        print("<complete add='true'>");
        $res = $connection->query($sql)->fetchAll();

        if ($res) {
            foreach ($res as $row) {
                print("<option value=\"" . $row["entity_id"] . "\">");
                print($row["lastname"] . ' ' . $row["firstname"]);
                print("</option>");
            }
        } else {

        }
        print("</complete>");
    }
}

?>
