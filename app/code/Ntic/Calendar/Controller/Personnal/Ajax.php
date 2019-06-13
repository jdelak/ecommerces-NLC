<?php
namespace Ntic\Calendar\Controller\Personnal;

use Ntic\Common\Helper\HandlerContactMaster;
use Magento\Framework\App\Bootstrap;


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
        // Récupère le code SELLER => [T001]
        $sellerCode = \Ntic\Calendar\Helper\CheckCustomerPermission::getUserSellerCode();

        $res = new \PDO("mysql:host=localhost;dbname=contact_master;charset=UTF8", "root", "Kay8lWiBMQwu");
        $dbtype = "PDO";

        if ($this->getRequest()->getMethod() == "GET") {
            print("<?xml version=\"1.0\"?>");
            if(strlen($this->params["mask"]) < 3)
            {
                print("<complete add='true'>");
                print("<option value= \"Null\" class='alert '>");
                print("Veuillez saisir au moins 3 charactères");
                print("</option>");
            } else {
                $sql = "SELECT	
                        c.id, 
                        c.lastname,
                        c.firstname,
                        c.email,
                        c.tel1,
                        c.tel2,
                        c.tel3,
                        c.prefix,
                        c.birthday,
                        a.contact_id,
                        a.addr1,
                        a.addr2,
                        a.addr3,
                        a.zip_code,
                        a.country,
                        a.city,
                        p.info_id,
                        i.attribute_id, 
                        i.value
                        FROM contact as c
                        LEFT JOIN contact_address as a ON c.id = a.contact_id
                        LEFT JOIN contact_profile as p ON c.id = p.contact_id
                        LEFT JOIN contact_info as i ON p.info_id = i.id
                        WHERE 
                        (
                        LOWER(c.lastname) like LOWER('%" . $this->params["mask"] . "%')
                        OR LOWER(c.firstname) like LOWER('%" . $this->params["mask"] . "%') 
                        OR LOWER(c.id) like LOWER('%" . $this->params["mask"] . "%'))
                        AND i.value = '".$sellerCode."'
                        LIMIT 0, 5";

                print("<complete add='true'>");
                $res = $res->query($sql)->fetchAll();

                if ($res) {
                        print("<div>Contact</div>");
                    foreach ($res as $row) {
                        print("<option value=\"" . intval($row["id"]) . "\">");
                        print($row["lastname"] . ' ' . $row["firstname"]);
                        print("</option>");
                    }
                } else {

                }
            }

            print("</complete>");
        }else {

        }

    }

//INSERT INTO `contact_info` (`id`, `attribute_id`, `value`) VALUES (NULL, '47', 'T0123');
}
?>

