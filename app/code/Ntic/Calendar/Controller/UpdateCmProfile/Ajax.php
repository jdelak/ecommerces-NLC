<?php
namespace Ntic\Calendar\Controller\UpdateCmProfile;


use Ntic\Common\Helper\HandlerContactMaster;


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
        $handler_contact_master = new HandlerContactMaster();
        // Récupère l'addresse IP
        $remote_address = $this->objectManager->get('Magento\Framework\HTTP\PhpEnvironment\RemoteAddress');
        $ip_address = $remote_address->getRemoteAddress();

        $id_contact = $this->params['contact_id'] ? $this->params['contact_id'] : '1';
        $event_id = $this->params['event_id'];
        $action = $this->params['action'];

        if ($action != "inserted")
        {
            $data_profile[] = array(
                'contact_id' => $id_contact,
                'type_id' => 3,
                'source' => 'Calendar-'.$action.'-event',
                'ext_id' => $event_id,
                'ip' => $ip_address,
                'updated_at' => date('Y-m-d H:i:s'),
            );
        } else {
            $data_profile[] = array(
                'contact_id' => $id_contact,
                'type_id' => 3,
                'source' => 'Calendar-'.$action.'-event',
                'ext_id' => $event_id,
                'ip' => $ip_address,
                'created_at' => date('Y-m-d H:i:s'),
            );
        }


        $handler_contact_master->save('contact_profile', $data_profile);
    }
}
?>
