<?php
namespace Ntic\Calendar\Controller\RdvDetails;

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

        $res = new \PDO("mysql:host=localhost;dbname=calendar_ntic;charset=UTF8", "root", "Kay8lWiBMQwu");

        if (isset($_POST['user_id'])) {
            /**
             * TOTAL DES RDV
             */
            $totalsql = "SELECT COUNT(*) AS total 
                    FROM events 	
                WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) 
          AND (MONTH(start_date) = MONTH('".date("Y-m-d H:i:s")."') AND MONTH(end_date) >= MONTH( '".date("Y-m-d H:i:s")."' ) )
                    AND userId= " . $_POST['user_id'];
            $total = $res->query($totalsql)->fetchAll(\PDO::FETCH_ASSOC);
            /**
             * TOTAL DES RDV POSITIONNES PAR BINOME
             */
            $binomesql = "SELECT COUNT(*) AS total
                    FROM events 	
                    WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) AND isBinome = 1 
                     AND (MONTH(start_date) = MONTH('".date("Y-m-d H:i:s")."') AND MONTH(end_date) >= MONTH( '".date("Y-m-d H:i:s")."' ) )
                    AND userId= " . $_POST['user_id'];
            $binome = $res->query($binomesql)->fetchAll(\PDO::FETCH_ASSOC);
            /**
             * TOTAL DES RDV RESTANT CE MOIS CI
             */
            $aFairesql = "SELECT COUNT(*) AS total
                    FROM events 	
                    WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) 
                    AND (start_date >= '".date("Y-m-d H:i:s")."' AND MONTH(end_date) >= MONTH( '".date("Y-m-d H:i:s")."' ) )
                    AND userId= " . $_POST['user_id'];
            $aFaire = $res->query($aFairesql)->fetchAll(\PDO::FETCH_ASSOC);
            /**
             * TOTAL RDV QUALIF VENDU
             */
            $ventesql = "SELECT COUNT(*) AS total
                    FROM events 	
                    WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) 
                    AND qualif = 2
                    AND (start_date >= MONTH( '".date("Y-m-d H:i:s")."' ) AND MONTH(end_date) <= MONTH( '".date("Y-m-d H:i:s")."' ) )
                    AND userId= " . $_POST['user_id'];
            $vente = $res->query($ventesql)->fetchAll(\PDO::FETCH_ASSOC);

            /**
             * TOTAL RDV QUALIF PUB
             */
            $pubsql = "SELECT COUNT(*) AS total
                    FROM events 	
                    WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) 
                    AND qualif = 4
                    AND (start_date >= MONTH( '".date("Y-m-d H:i:s")."' ) AND MONTH(end_date) >= MONTH( '".date("Y-m-d H:i:s")."' ) )
                    AND userId= " . $_POST['user_id'];
            $pub = $res->query($pubsql)->fetchAll(\PDO::FETCH_ASSOC);

            /**
             * TOTAL RDV QUALIF ANNULE
             */
            $annulesql = "SELECT COUNT(*) AS total
                    FROM events 	
                    WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) 
                    AND qualif = 3
                    AND (start_date >= MONTH( '".date("Y-m-d H:i:s")."' ) AND MONTH(end_date) >= MONTH( '".date("Y-m-d H:i:s")."' ) )
                    AND userId= " . $_POST['user_id'];
            $annule = $res->query($annulesql)->fetchAll(\PDO::FETCH_ASSOC);
            /**
             * TOTAL RDV QUALIF REPORT
             */
            $reportsql = "SELECT COUNT(*) AS total
                    FROM events 	
                    WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) 
                    AND qualif = 5
                    AND (start_date >= MONTH( '".date("Y-m-d H:i:s")."' ) AND MONTH(end_date) >= MONTH( '".date("Y-m-d H:i:s")."' ) )
                    AND userId= " . $_POST['user_id'];
            $report = $res->query($reportsql)->fetchAll(\PDO::FETCH_ASSOC);
            /**
             * TOTAL RDV QUALIF ABSENT
             */
            $abssql = "SELECT COUNT(*) AS total
                    FROM events 	
                    WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) 
                    AND qualif = 6
                    AND (start_date >= MONTH( '".date("Y-m-d H:i:s")."' ) AND MONTH(end_date) >= MONTH( '".date("Y-m-d H:i:s")."' ) )
                    AND userId= " . $_POST['user_id'];
            $abs = $res->query($abssql)->fetchAll(\PDO::FETCH_ASSOC);
            /**
             * ON RETOURNE LE JSON
             */
            echo json_encode(array (
                                    'total'  => $total,
                                    'binome' => $binome,
                                    'aFaire' => $aFaire,
                                    'vente' => $vente,
                                    'annule' => $annule,
                                    'report' => $report,
                                    'abs' => $abs,
                                    'pub' => $pub
                                    )
                            );
        }
    }
}