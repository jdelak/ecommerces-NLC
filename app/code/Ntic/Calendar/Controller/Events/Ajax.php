<?php
namespace Ntic\Calendar\Controller\Events;

use DHTMLX_Scheduler\Helper;
use Dhtmlx\Connector\GridConnector;
use Dhtmlx\Connector\SchedulerConnector;
use Dhtmlx\Connector\Connector;

class Ajax extends \Magento\Framework\App\Action\Action
{
    protected $resultFactory;
    protected $jsonHelper;
    private $params;
    private $user_id;
    private $scheduler;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(\Magento\Backend\App\Action\Context $context)
    {
        parent::__construct($context);
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->resource = $this->objectManager->get('Magento\Framework\App\ResourceConnection');
        $this->_db = $this->resource->getConnection();
        $this->params = $this->getRequest()->getParams();
        $this->user_id = intval($_GET['user_id']);
    }

    public function execute(){

        $helper = new Helper(
            array(
                "dbsm" => "mysql", // optional, "mysql" by default
                "host" => "localhost", // optional, "localhost" by default
                "db_name" => "calendar_ntic",
                "user" => "calendar_ntic",
                "password" => "v4rtuE4ZP8Ub",
                "table_name" => "events_rec" // name of the table that contains data of recurring events
            )
        );
        
        $res = new \PDO('mysql:host=localhost;dbname=calendar_ntic','calendar_ntic','v4rtuE4ZP8Ub', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $dbtype = 'PDO';

        // AJOUT LISTE DEROULANTE TYPE
        $list = new \Dhtmlx\Connector\OptionsConnector($res, $dbtype);
        $list->render_table("types","typeid","typeid(value),name(label)");
        // AJOUT LISTE DEROULANTE QUALIF
        $qualif= new \Dhtmlx\Connector\OptionsConnector($res, $dbtype);
        $qualif->render_table("qualif","q_id","q_id(value),q_label(label)");
        // AJOUTS DES RENDU SUR LE SCHEDULER
        $this->scheduler = new \Dhtmlx\Connector\SchedulerConnector($res, $dbtype);
        $this->scheduler->set_options("type", $list);
        $this->scheduler->set_options("qualif", $qualif);

        // Recupere les groupe de la personne connecter et verifie si il est manager
        $manager =  \Ntic\Calendar\Helper\CheckCustomerPermission::isManager();
        // Interdiction de supprimer un event si pas manager
        if ($manager == false) {
            $this->scheduler->access->deny("delete");
        }
//        $this->scheduler->useModel(self::class);
//        $this->scheduler->event->attach("beforeProcessing", array(self::class,"delete_related"));
//        $this->scheduler->event->attach("beforeProcessing", array(self::class,"checkData"));
//        $this->scheduler->event->attach("beforeProcessing", array(self::class,"delete_related"));
//        $this->scheduler->event->attach("afterProcessing",  array(self::class,"insert_related"));

//       $scheduler->sql->attach("delete","DELETE FROM events WHERE id = '{id}'");
//       $scheduler->access->deny("delete");

        $this->scheduler->render_sql("SELECT * FROM events WHERE userId = ".$this->user_id,"event_id","start_date,end_date,event_name,details,rec_type,event_pid,event_length,type,contact,userId,qualif, isCustomer, isBinome");
    }

     public function delete_related($action){
        global $scheduler;

        $status = $action->get_status();
        $type =$action->get_value("rec_type");
        $pid = ($action->get_value("event_pid")==='')?0:$action->get_value("event_pid");
        //when serie changed or deleted we need to remove all linked events
        if (($status == "deleted" || $status == "updated") && $type!=""){
            $this->scheduler->sql->query("DELETE FROM events_rec WHERE event_pid='".$scheduler->sql->escape($action->get_id())."'");
        }
        if ($status == "deleted" && $pid !=0){
            $this->scheduler->sql->query("UPDATE events_rec SET rec_type='none' WHERE event_id='".$scheduler->sql->escape($action->get_id())."'");
            $action->success();
        }
    }

    public function insert_related($action){
        $status = $action->get_status();
        $type =$action->get_value("rec_type");

        if ($status == "inserted" && $type=="none")
            $action->set_status("deleted");
    }

     public function checkData($action){
        $status = $action->get_status();
        $event_pid =$action->get_value("event_pid");
        $event_length =$action->get_value("event_length");

        if(($status == 'inserted' || $status == 'updated' ) && $event_pid ==''){
            $action->set_value("event_pid",0);
        }
        if(($status == 'inserted' ||$status == 'updated' ) && $event_length ==''){
            $action->set_value("event_length",0);
        }
    }

    public function default_values($action){
        global $user_id;
        $event_type = $action->get_value("event_type");
        if ($event_type == "")
            $event_type = 0;
        $action->set_value("userId",$this->user_id);
    }
}
