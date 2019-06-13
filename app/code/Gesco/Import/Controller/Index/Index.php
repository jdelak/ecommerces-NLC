<?php


namespace Gesco\Import\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    private $orderFactory;
    protected $resultPageFactory;
    private $pdo;
    private $pdo2;
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->orderFactory = $orderFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->pdo = new \PDO('mysql:dbname=e_keops;host=localhost', 'root', 'Kay8lWiBMQwu');
        $this->pdo2 = new \PDO('mysql:dbname=image_gesco_RO;host=localhost', 'root', 'Kay8lWiBMQwu');
        parent::__construct($context);
    }

    public function execute()
    {
        $orderIdList = array();//Tableau contenant les id des commandes expédiées en Gesco
        $ordersToModify = array(); // Tableau contenant les increment_id des commandes avec le statut exported dans Magento
        $ordersShipped = array(); // Tableau contenant les id des commandes livrées en Gesco
        $orderstoComplete = array(); // Tableau contenant les increment_id des commandes avec le statut in_delivering dans Magento

        //On récupère la liste des commandes gesco qui ont été expédié on prend leur id et on l'envoi dans un tableau
        $listColis = $this->getColisFromGesco();
        foreach($listColis as $colis){
            $cmd = $colis['clncde'];
            array_push($orderIdList, $cmd);
        }

        //On récupère la liste des commandes magento qui ont le statut "exported", on prend leur id et on l'envoi dans un tableau
        $listOrder = $this->getOrderExported();
        foreach($listOrder as $orderExported){
            $orderId = $orderExported['increment_id'];
            array_push($ordersToModify, substr($orderId,3));
        }

        //On récupère la liste des commandes gesco qui ont été livré, on prend leur id et on l'envoi dans un tableau
        $listOrderShipped = $this->getOrderShippedFromGesco();
        if(!empty($listOrderShipped)) {
            foreach ($listOrderShipped as $ordshipped) {
                $cmdshipped = $ordshipped['gcencde'];
                array_push($ordersShipped, $cmdshipped);
            }
        }

        //On récupère la liste des commandes magento qui ont le statut "in_delivering", on prend leur id et on l'envoi dans un tableau
        $listOrderComplete = $this->getOrderDelivering();
        if(!empty($listOrderComplete)){
            foreach($listOrderComplete as $ordercomplete){
                $cmdcomplete = $ordercomplete['increment_id'];
                array_push($orderstoComplete, substr($cmdcomplete,3));
            }
        }
        

       $ordersIntersect = array_intersect($orderIdList, $ordersToModify);
        foreach($ordersIntersect as $orderDeliveringState){
            $incrementId = '300'.$orderDeliveringState;
            $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
            $order->setState("in_delivering")->setStatus("in_delivering");
            $order->save();
        }

        $ordersIntersect2 = array_intersect($ordersShipped, $orderstoComplete);

        foreach($ordersIntersect2 as $orderCompleteState){
            $incrementId2 = '300'.$orderCompleteState;
            $order2 = $this->orderFactory->create()->loadByIncrementId($incrementId2);
//            $orderId2 = $order2->getId();
            $order2->setState("complete")->setStatus("complete");
            $order2->save();
        }

    }

    private function getOrderExported(){
        $query = "SELECT * FROM `sales_order` WHERE status = 'exported' AND store_id = 3 AND increment_id >= '3008017160'";
        return $this->pdo->query($query);
    }

    private function getColisFromGesco(){
        $query = "SELECT * FROM `lxcollex` WHERE clncde BETWEEN '8017160' AND '8999999'";
        return $this->pdo2->query($query);
    }

    private function getOrderDelivering(){
        $query = "SELECT * FROM `sales_order` WHERE status = 'in_delivering' AND store_id = 3 AND increment_id >= '3008017160'";
        return $this->pdo->query($query);
    }

    private function getOrderShippedFromGesco(){
        $query = "SELECT * FROM `gcdeent` WHERE gcelivr = 'O' AND gcencde BETWEEN '8017160' AND '8999999'";
        return $this->pdo2->query($query);
    }

    private function setStateComplete($orderId){

        $query = "UPDATE `sales_order` SET state = 'complete', status = 'complete' WHERE entity_id =".$orderId;
        return $this->pdo->query($query);

    }



}