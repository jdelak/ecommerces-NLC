<?php
namespace Ntic\Calendar\Controller\Contact;

use Ntic\Common\Helper\HandlerContactMaster;


class Submit extends \Magento\Framework\App\Action\Action {
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
        // Instance of Handler sponsor (helper)
        $handler_contact_master = new HandlerContactMaster();
        if ($this->getRequest()) {
            if (isset($this->params['save'])) {
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
                        'addr1' => isset($this->params['contact_adr1']) ? (int)$this->params['contact_adr1'] : '',
                        'addr2' => isset($this->params['contact_adr2']) ? (int)$this->params['contact_adr2'] : '',
                        'addr3' => isset($this->params['contact_adr3']) ? (int)$this->params['contact_adr3'] : '',
                        'zip_code' => isset($this->params['contact_cp']) ? $this->params['contact_cp'] : '',
                        'country' => isset($this->params['contact_pays']) ? $this->params['contact_pays'] : '',
                        'city' => isset($this->params['contact_ville']) ? $this->params['contact_ville'] : '',
                        'created_at' => date('Y-m-d H:i:s'),
                    );

                    $handler_contact_master->save('contact_address', $data_address);

                    $data_profile[] = array(
                        'contact_id' => $id_contact,
                        'type_id' => 3,
                        'source' => 'Calendar',
                        'ext_id' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $handler_contact_master->save('contact_profile', $data_profile);

                }else {
                   die('Adresse mail déja présente dans Contact Master');
                }
            }
        }
    }

}
?>