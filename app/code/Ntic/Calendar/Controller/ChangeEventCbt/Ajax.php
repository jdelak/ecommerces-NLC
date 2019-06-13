<?php
namespace Ntic\Calendar\Controller\ChangeEventCbt;

error_reporting(E_ALL);
ini_set('display_errors', 1);
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
        $this->_db = new \PDO('mysql:host=localhost;dbname=calendar_ntic','root','Kay8lWiBMQwu');
        $this->params = $this->getRequest()->getParams();
    }


    public function execute()
    {
        $eventSql = "SELECT * FROM events WHERE event_id =" .$this->params['event_id'];
        $rdv = $this->_db->query($eventSql)->fetch();

        $event_user_sql =  "SELECT * FROM events WHERE start_date >= '". date("Y-m-d H:i:s") ."' AND userId = " . $this->params['new_cbt'];
        $rdv_by_user = $this->_db->query($event_user_sql)->fetchAll();


        foreach ($rdv_by_user as $date_rdv) {
            if ( ( $rdv['start_date']>= $date_rdv['start_date'] && $rdv['end_date'] <= $date_rdv['end_date']) ) {
                return false;
            }
        }

        if (is_numeric($this->params['new_cbt']))
        {
            $sql= "UPDATE events SET userId = " . $this->params['new_cbt'] . " WHERE event_id = " .$this->params['event_id'];
            $this->_db->exec($sql);
            return true;
        }
        return false;
    }
}

?>
