<?php
namespace Ntic\Calendar\Controller\Contact;

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

        $res = new \PDO("mysql:host=localhost;dbname=contact_master;charset=UTF8", "root", "Kay8lWiBMQwu");
        $dbtype = "PDO";

        if ($this->getRequest()->getMethod() == "GET") {
            print("<?xml version=\"1.0\"?>");
            if(strlen($this->params["mask"]) < 3)
            {
                print("<complete add='true'>");
                print("<option value= \"Null\">");
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
                        a.city
                        FROM contact as c
                        LEFT JOIN contact_address as a ON c.id = a.contact_id
                        WHERE 
                        LOWER(c.lastname) like LOWER('" . $this->params["mask"] . "%') 
                        OR LOWER(c.firstname) like LOWER('" . $this->params["mask"] . "%') 
                        OR LOWER(c.id) like LOWER('" . $this->params["mask"] . "%')
                        LIMIT 0, 15";

                print("<complete add='true'>");
                $res = $res->query($sql)->fetchAll();

                if ($res) {
                    print("<option value= \"Null\">Prospect</option>");
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

