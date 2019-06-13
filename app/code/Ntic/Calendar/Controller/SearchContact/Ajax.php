<?php
namespace Ntic\Calendar\Controller\SearchContact;

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

        $customerSession = $this->objectManager->get('Magento\Customer\Model\Session');
        // Si le manager est connecté et a choisis un vendeur alors $customer_id aura la valeur du parametre $_GET
        if (isset($_GET['manager']) && $_GET['manager'] == true ) {
            if (isset($_GET['user_id'])) {
                $customer_id = $_GET['user_id'];
            }
        } else {
            $customer_id = $customerSession->getData('customer_id');
        }

        $res = new \PDO("mysql:host=localhost;dbname=contact_master;charset=UTF8", "root", "Kay8lWiBMQwu");
        $dbtype = "PDO";
        $connection = $this->resource->getConnection();
        $tableCustomer = $this->resource->getTableName('customer_entity');

        if ($this->getRequest()->getMethod() == "GET") {
            
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
                    a.city
                    FROM contact as c
                    LEFT JOIN contact_address as a ON c.id = a.contact_id
                    WHERE 
                    LOWER(c.lastname) like LOWER('" . $this->params["keyword"] . "%') 
                    OR LOWER(c.firstname) like LOWER('" . $this->params["keyword"] . "%') 
                    OR LOWER(c.id) like LOWER('" . $this->params["keyword"] . "%')
                    LIMIT 0, 10";

            $sql2 = "SELECT 
                    customer_entity.entity_id,
                    customer_entity.firstname, 
                    customer_entity.lastname,
                    email
                    FROM " . $tableCustomer . " 
                    LEFT JOIN  
                    ntic_portfolio_customer ON ntic_portfolio_customer.customer_id = ".$tableCustomer.".entity_id
                    WHERE ntic_portfolio_customer.seller_id = ". intval($this->params["seller_id"]) ." 
                    AND (
                          LOWER(customer_entity.entity_id) LIKE LOWER('" . $this->params["keyword"] . "%') 
                            OR   LOWER(customer_entity.firstname) LIKE LOWER('" . $this->params["keyword"] . "%') 
                            OR   LOWER(customer_entity.lastname) LIKE LOWER('" . $this->params["keyword"] . "%')
                        )
                        LIMIT 0, 3";

            


            $res = $res->query($sql)->fetchAll();
            $res2 = $connection->query($sql2)->fetchAll();
            echo "<ul id='contact-list'>";
            if ($res) {

                echo "<h4 id='prospects'>Prospects</h4>";
                foreach ($res as $row) {

                    echo"<li>";
                    echo "<span class='contact-item' id=\"" . intval($row["id"]) . "\">". $row["lastname"] . ' ' . $row["firstname"]."</span>";
                    echo " <button id=\"btn-" . intval($row["id"]) . "\"><i class='fa fa-pencil' aria-hidden='true' data-toggle='modal' data-target='#modalSaveContact'></i></button></li>";
                }


            } else {

            }



            if ($res2) {
                echo "<h4 id='clients'>Clients</h4>";
                foreach ($res2 as $row2) {

                    echo"<li class='contact-item' id=\"" . intval($row2["entity_id"]) . "\">";
                    echo $row2["lastname"] . ' ' . $row2["firstname"];
                    echo "</li>";
                }
            } else {


            }
            echo "</ul>";


        }else {
            // Si la methode est POST
            if ($this->getRequest()->getMethod() == "POST") {
                // Si la requete est une sauvegarde de nouveau contact
                if (isset($this->params['action']) && $this->params['action'] == "save") {
                    self::saveContact();
                } elseif (isset($this->params['action'])&& $this->params['action'] == "update") {
                    self::updateContact();
                }
            }
        }

    }

    public function saveContact()
    {
        // Récupère le code SELLER => [T001]
        $sellerCode = \Ntic\Calendar\Helper\CheckCustomerPermission::getUserSellerCode();
        // Récupère l'addresse IP
        $remote_address = $this->objectManager->get('Magento\Framework\HTTP\PhpEnvironment\RemoteAddress');
        $ip_address = $remote_address->getRemoteAddress();
        $message="";
        // Instance of Handler sponsor (helper)
        $handler_contact_master = new HandlerContactMaster();
        if ($this->getRequest()) {
            if (isset($this->params['action']) && $this->params['action'] == "save") {
//               if (empty($this->params['contact_mail']))
//               {
//                   $resultRedirect = $this->resultRedirectFactory->create();
//                   $this->messageManager->addError( __("L'email ne peut être vide") );
//                   $customerBeforeAuthUrl = $this->_url->getUrl('calendar/', array('referer' => $objecturl->getEncodedUrl($this->_url->getUrl(''))));
//                   return $resultRedirect->setPath($customerBeforeAuthUrl);
//               }
                $check_contact = $handler_contact_master->isContactExist('email', $this->params['contact_mail']);
                if ($check_contact == false) {
                    $data_contact[] = array(
                        'prefix' => isset($this->params['contact_civ']) ? $this->params['contact_civ'] : '',
                        'lastname' => isset($this->params['contact_prenom']) ? $this->params['contact_prenom'] : '',
                        'firstname' => isset($this->params['contact_nom']) ? $this->params['contact_nom'] : '',
                        'email' => isset($this->params['contact_mail']) ? $this->params['contact_mail'] : '',
                        'tel1' => isset($this->params['contact_tel1']) ? (int)$this->params['contact_tel1'] : '',
                        'tel2' => isset($this->params['contact_tel2']) ? (int)$this->params['contact_tel2'] : '',
                        'tel3' => isset($this->params['contact_tel3']) ? (int)$this->params['contact_tel3'] : '',
                        'birthday' => $this->params['contact_birthday']  = $this->params['contact_birthday'] ? (int)$this->params['contact_birthday'] : '0000-00-00',
                        'created_at' => date('Y-m-d H:i:s'),
                        'exploited_at' => date('Y-m-d H:i:s'),
                    );

                    $handler_contact_master->save('contact', $data_contact);

                    $id_contact = $handler_contact_master->getLastId();

                    $data_address[] = array(
                        'contact_id' => $id_contact,
                        'addr1' => isset($this->params['contact_adr1']) ? $this->params['contact_adr1'] : '',
                        'addr2' => isset($this->params['contact_adr2']) ? $this->params['contact_adr2'] : '',
                        'addr3' => isset($this->params['contact_adr3']) ? $this->params['contact_adr3'] : '',
                        'zip_code' => isset($this->params['contact_cp']) ? $this->params['contact_cp'] : '',
                        'country' => isset($this->params['contact_pays']) ? $this->params['contact_pays'] : '',
                        'city' => isset($this->params['contact_ville']) ? $this->params['contact_ville'] : '',
                        'created_at' => date('Y-m-d H:i:s'),
                    );

                    $handler_contact_master->save('contact_address', $data_address);

                    $data_infos[]=array(
                        'attribute_id' => '47',
                        'value' => $sellerCode,
                    );
                    $handler_contact_master->save('contact_info', $data_infos);
                    $id_contact_infos = $handler_contact_master->getLastId();

                    $data_profile[] = array(
                        'contact_id' => $id_contact,
                        'info_id' => $id_contact_infos,
                        'type_id' => 3,
                        'source' => 'Calendar-new-contact',
                        'ip' => $ip_address,
                        'ext_id' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $handler_contact_master->save('contact_profile', $data_profile);


                    $resultRedirect = $this->resultRedirectFactory->create();
                    $this->messageManager->addSuccess( __('Contact ajouté dans la base de données ou mis àjour correctement') );
                    $customerBeforeAuthUrl = $this->_url->getUrl('calendar/', array('referer' => $objecturl->getEncodedUrl($this->_url->getUrl(''))));
                    return $resultRedirect->setPath($customerBeforeAuthUrl);
                }else {

                    $resultRedirect = $this->resultRedirectFactory->create();
                    $this->messageManager->addError( __('Adresse mail déja présente dans Contact Master ou erreur lors de l\'enregistrement') );
                    $customerBeforeAuthUrl = $this->_url->getUrl('calendar/', array('referer' => $objecturl->getEncodedUrl($this->_url->getUrl(''))));
                    return $resultRedirect->setPath($customerBeforeAuthUrl);
                }
            }
        }
        return $message;
    }

    public function updateContact()
    {

        $handler_contact_master = new HandlerContactMaster();
        /**
         * UPDATE CONTACT
         */
        $id_contact=[
            'id' => $this->params['contact_id']
        ];
        $data_contact[] = array(
            'prefix' => isset($this->params['contact_civ']) ? $this->params['contact_civ'] : '',
            'lastname' => isset($this->params['contact_prenom']) ? $this->params['contact_prenom'] : '',
            'firstname' => isset($this->params['contact_nom']) ? $this->params['contact_nom'] : '',
            'email' => isset($this->params['contact_mail']) ? $this->params['contact_mail'] : '',
            'tel1' => isset($this->params['contact_tel1']) ? (int)$this->params['contact_tel1'] : '',
            'tel2' => isset($this->params['contact_tel2']) ? (int)$this->params['contact_tel2'] : '',
            'tel3' => isset($this->params['contact_tel3']) ? (int)$this->params['contact_tel3'] : '',
            'birthday' => $this->params['contact_birthday']  = $this->params['contact_birthday'] ? (int)$this->params['contact_birthday'] : '0000-00-00',
            'created_at' => date('Y-m-d H:i:s'),
            'exploited_at' => date('Y-m-d H:i:s'),
        );

        $handler_contact_master->update($id_contact, 'contact', $data_contact);

        /**
         * UPDATE ADRESSE CONTACT
         */
        $id_contact_address=[
            'contact_id' => $this->params['contact_id']
        ];
        $data_address[] = array(
            'addr1' => isset($this->params['contact_adr1']) ? $this->params['contact_adr1'] : '',
            'addr2' => isset($this->params['contact_adr2']) ? $this->params['contact_adr2'] : '',
            'addr3' => isset($this->params['contact_adr3']) ? $this->params['contact_adr3'] : '',
            'zip_code' => isset($this->params['contact_cp']) ? $this->params['contact_cp'] : '',
            'country' => isset($this->params['contact_pays']) ? $this->params['contact_pays'] : '',
            'city' => isset($this->params['contact_ville']) ? $this->params['contact_ville'] : '',
            'created_at' => date('Y-m-d H:i:s'),
        );

        $handler_contact_master->update($id_contact_address, 'contact_address', $data_address);
    }
}
?>

