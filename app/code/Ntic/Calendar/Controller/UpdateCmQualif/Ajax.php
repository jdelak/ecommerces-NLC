<?php
namespace Ntic\Calendar\Controller\UpdateCmQualif;


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
        $_db = new \PDO('mysql:host=localhost;dbname=calendar_ntic','root','Kay8lWiBMQwu');

        $qualif_id= $this->params['qualif_id'];
        $event_id = $this->params['event_id'];

        $sql = "UPDATE events SET events.qualif = '".$qualif_id."' WHERE events.event_id = " . $event_id;
        $_db->exec($sql);
    }
}
?>
