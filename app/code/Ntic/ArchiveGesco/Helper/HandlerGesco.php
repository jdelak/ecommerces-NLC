<?php
    namespace Ntic\ArchiveGesco\Helper;
    use Magento\Framework\App\ObjectManager;

    class HandlerGesco extends \Magento\Framework\App\Helper\AbstractHelper {
        public $pdo;

        //\Ntic\Sponsor\Helper\HandlerSponsor $handlerSponsor
        public function __construct() {
            // Connection to database
            $this->pdo = new \PDO('mysql:dbname=image_gesco_RO;host=localhost', 'root', 'Kay8lWiBMQwu');

            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        /**
         * execute query
         *
         * @param the query
         * @return the result
         */
        public function query($query) {
            return $this->pdo->query($query);
        }

        /**
         * Prepare query
         *
         * @param sql query
         * @param array of value
         */
        protected function prepare($query, $array) {
            $stmt = $this->pdo->prepare($query) or die(print_r($this->pdo->errorInfo()));
            $stmt->execute($array);
        }

        /**
         * Get the last id
         *
         * @return result
         */
        public function getLastId() {
            return $this->pdo->lastInsertId();
        }

        /**
         * Get SQL list
         *
         * @param the query
         * @return the result list
         */
        public function getList($query) {
            $result = $this->query($query);
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        }

        /**
         * Get one result
         *
         * @param the query
         * @return one result
         */
        public function getOne($query) {
            $result = $this->query($query);
            return $result->fetch(\PDO::FETCH_ASSOC);
        }

        /**
         * Simple query with exec
         *
         * @param $query
         */
        public function queryExec($query) {
            $stmt = $this->query($query);
            $stmt->execute();
        }

        /**
         * Get count
         *
         *
         * @param the query
         * @return int
         */
        public function getCount($query) {
            $result = $this->query($query);
            return count($result->fetchAll(\PDO::FETCH_ASSOC));
        }

        /**
         * Request and Export
         */
        public function exportUsers($file) {
            return $this->queryExec("
                SELECT gclcode, gclnom, gclpren, gcldpcd, gclddcd
                INTO OUTFILE '".$file."'
                  FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"'
                  LINES TERMINATED BY '\n'
                FROM gcliact;
            ");
        }

        /**
         * Récup le client par son id
         *
         * @param $id
         * @return the
         */
        public function getUserById($id) {
            return $this->getOne("
                SELECT 
                    gclcode as client_id,
                    gcltitr as gender,
                    gclnom  as lastname,
                    gclpren as firstname,
                    gcldatn as birthday,
                    gcladr1 as addr1,
                    gcladr2 as addr2,
                    gcladr3 as addr3,
                    gclcpos as zip_code,
                    gclvill as city,
                    gclpays as country,
                    gcltele as tel1,
                    gcltel2 as tel2,
                    gclfax  as fax,
                    gclmail as email,
                    gclddcd as last_order,
                    gcldtcr as created_date,
                    gclnbcd as number_order
                FROM gcliact
                WHERE gclcode = ".$id."
            ");
        }

        /**
         * Recup les commandes associées au client
         *
         * @param $client_id
         * @return SQL
         */
        public function getOrdersByClientId($client_id) {
            return $this->getList("
                SELECT
                    gcencde as order_id,
                    gcecli  as client_id,
                    gcenfac as number_bill,
                    gcedcde as order_date,
                    gcemttc as amount_ttc
                FROM gcdeent
                WHERE gcecli = ".$client_id."
            ");
        }

        /**
         * Recup le client id par la commande
         *
         * @param $order_id
         * @return SQL
         */
        public function getClientIdByOrderId($order_id) {
            return $this->getOne("
                SELECT 
                    gcencde as order_id, 
                    gcecli  as client_id 
                FROM gcdeent
                WHERE gcencde = ".$order_id."
            ");
        }

        public function getClientIdBySubscriptionId($subscription_id) {
            return $this->getOne("
                SELECT 
                    cancon as subscription_id, 
                    caclie  as client_id 
                FROM lxctrabo
                WHERE cancon = ".$subscription_id."
            ");
        }

        /**
         * Recup la commande par l'order id
         */
        public function getOrderByOrderId($order_id) {
            return $this->getOne("
                SELECT 
                    gcencde as order_id,
                    gcecli  as client_id,
                    gcenfac as number_bill,
                    gcedcde as order_date,
                    gcemttc as amount_ttc 
                FROM gcdeent
                LEFT JOIN gcdelig ON gcdelig.gcancde = gcdeent.gcencde
                WHERE gcencde = ".$order_id."
            ");
        }

        /**
         * Recup l'abonnement par l'order_id
         */
        public function getSubscriptionBySubscriptionId($subscription_id) {
            return $this->getOne("
                SELECT
                    catype as type,
                    canbmo as number_month,
                    cancon as number_contract,
                    carefa as ref_contract,
                    caclie as client_id,
                    cadata as date_activated,
                    cadatf as date_end_subscription
                FROM lxctrabo 
                WHERE caclie = ".$subscription_id ."
            ");
        }

        /**
         * Recup les abo de type LBOX
         */
        public function getClubCardByRefSubscription($ref) {
            return $this->getOne("
                SELECT 
                    eatype as type,
                    earefa as ref_contract,
                    eanoma as card_club_name,
                    eapttc as amount
                FROM lxentabo
                WHERE earefa = '".$ref."'
            ");
        }

        /**
         * Récup les produits assoccié à un abonnement
         */
        public function getSubscriptionProductsByRefSubscription($ref) {
            return $this->getList("
                SELECT 
                    darefa  as ref,
                    dalign  as prodcuts_order,
                    daprod  as ref_products,
                    gprdesi as products_name
                FROM lxdetabo
                INNER JOIN gprodui ON gprodui.gprcode = lxdetabo.daprod
                WHERE darefa = '".$ref."'
            ");
        }

        /**
         * Recup l'adresse de livraison
         */
        public function getDeliveryData($order_id) {
            return $this->getOne("
                SELECT 
                    gaencde as order_id,
                    gaedexp as wish_date,
                    gaenoml as shipping_name,
                    gaetras as transport_mode
                FROM gcdeaex 
                WHERE gaencde = ".$order_id."
            ");
        }

        /**
         * Recup l'adresse d'expedition
         */
        public function getShippingAddress($order_id) {
            return $this->getOne("
                SELECT
                    gadncde as order_id,
                    gadtitr as gender,
                    gadnom  as lastname,
                    gadpren as firstname,
                    gadadr1 as addr1,
                    gadadr2 as addr2,
                    gadadr3 as addr3,
                    gadcpos as zip_code,
                    gadvill as city,
                    gadpays as country
                FROM gadrliv 
                WHERE gadncde = ".$order_id."
            ");
        }

        /**
         * Recup les produits de la commande
         */
        public function getProductsByOrderId($order_id) {
            return $this->getList("
                SELECT 
                    gcancde as order_id,
                    gprdesi as products_name,
                    gcaprxv as products_price
                FROM gcdelig, gprodui 
                WHERE gcancde = ".$order_id." 
                AND gprcode = gcaprod
            ");
        }

        /**
         * Recup les abo associées au client
         *
         * @param $client_id
         * @return SQL
         */
        public function getSubscriptionsByClientId($client_id) {
            return $this->getList("
                SELECT
                    cancon as number_contract,
                    carefa as ref_contract,
                    caclie as client_id,
                    cadata as date_activated,
                    cadatf as date_end_subscription
                FROM lxctrabo
                WHERE caclie = ".$client_id."
            ");
        }

        /**
         * Recup les informations des colis
         */
        public function getSubscriptionParcelBySubcriptionId($subscription_id) {
            return $this->getList("
                SELECT 
                    lancon as subscription_id,
                    ladexs as wish_date,
                    ladexr as real_date,
                    lancol as parcel_number,
                    lanfac as bill_number
                FROM lxlivabo
                WHERE lancon = ".$subscription_id."
            ");
        }

    }