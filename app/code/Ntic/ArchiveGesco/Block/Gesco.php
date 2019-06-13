<?php

    namespace Ntic\ArchiveGesco\Block;
    use Ntic\ArchiveGesco\Helper\HandlerArchiveGesco;

    class Gesco extends \Magento\Framework\View\Element\Template {
        public $handler_gesco;

        public function __construct(\Magento\Framework\View\Element\Template\Context $context) {
            parent::__construct($context);
            // Appel helper "HandlerGesco"
            $this->handler_gesco = \Magento\Framework\App\ObjectManager::getInstance()->get("\Ntic\ArchiveGesco\Helper\HandlerGesco");
            
            // GET
            $this->requestGet = $this->getRequest()->getParams();
        }

        /**
         * Construit un array complet des données du client et de ses commandes
         */
        public function indexData() {
            // CLIENT
            $data['customer'] = $this->getUser($this->requestGet['id']);

            // COMMANDE
            $data['order'] = $this->getOrders($this->requestGet['id']);

            // ABO
            $data['subscription'] = $this->getSubscriptions($this->requestGet['id']);

            return $data;
        }

        /**
         * Construit un array complet des données de la commande
         */
        public function orderData() {
            // ORDER
            $data['order'] = $this->getOrder($this->requestGet['order_id']);

            // PRODUIT
            $data['products'] = $this->getProducts($this->requestGet['order_id']);

            // SHIPPING / DELIVERY
            $data['shipping'] = $this->getShippingAddress($this->requestGet['order_id']);
            $data['delivery'] = $this->getDeliveryData($this->requestGet['order_id']);

            return $data;
        }

        /**
         * Construit un array complet des données de l'abonnement
         */
        public function subscriptionData() {
            // ORDER
            $data['subscription'] = $this->getSubscription($this->requestGet['id']);
            
            // PRODUITS
            if($data['subscription']['type'] == 'A') {
                $data['club_card'] = $this->getClubCard($data['subscription']['ref_contract']);
            } else {
                $data['subscription_products'] = $this->getSubscriptionProducts($data['subscription']['ref_contract']);
            }

            // PARCEL
            $data['parcel'] = $this->getParcel($this->requestGet['subscription_id']);

            // SHIPPING / DELIVERY
            $data['shipping'] = $this->getShippingAddress($this->requestGet['subscription_id']);
            $data['delivery'] = $this->getDeliveryData($this->requestGet['subscription_id']);

            return $data;
        }

        /**
         * Récup le client par son id
         */
        public function getUser($id) {
            return $this->handler_gesco->getUserById($id);
        }

        /**
         * Récup l'abonnement par le client id
         */
        public function getSubscriptions($id) {
            return $this->handler_gesco->getSubscriptionsByClientId($id);
        }

        /**
         * Recup les LBOX
         */
        public function getClubCard($ref) {
            return $this->handler_gesco->getClubCardByRefSubscription($ref);
        }

        /**
         * Recup les produits d'un abonnement
         */
        public function getSubscriptionProducts($ref) {
            return $this->handler_gesco->getSubscriptionProductsByRefSubscription($ref);
        }

        /**
         * Récup le client_id par la commande
         */
        public function getClientIdByOrder() {
            return $this->handler_gesco->getClientIdByOrderId($this->requestGet['order_id'])['client_id'];
        }

        /**
         * Récup le client_id par l'abonnement
         */
        public function getClientIdBySubscription() {
            return $this->handler_gesco->getClientIdBySubscriptionId($this->requestGet['subscription_id'])['client_id'];
        }

        /**
         * Récup la commande
         */
        public function getOrder($order_id) {
            return $this->handler_gesco->getOrderByOrderId($order_id);
        }

        /**
         * Récup la commande par le client id
         */
        public function getOrders($id) {
            return $this->handler_gesco->getOrdersByClientId($id);
        }

        /**
         * Récup la commande
         */
        public function getSubscription($subscription_id) {
            return $this->handler_gesco->getSubscriptionBySubscriptionId($subscription_id);
        }

        /**
         * Récup l'adresse de livraison
         */
        public function getDeliveryData($order_id) {
            return $this->handler_gesco->getDeliveryData($order_id);
        }

        /**
         * Recup l'adresse d'expedition
         */
        public function getShippingAddress($order_id) {
            return $this->handler_gesco->getShippingAddress($order_id);
        }

        /**
         * Recup les produits de la commande
         */
        public function getProducts($order_id) {
            return $this->handler_gesco->getProductsByOrderId($order_id);
        }

        /**
         * Recup les colis
         */
        public function getParcel($subscription_id) {
            return $this->handler_gesco->getSubscriptionParcelBySubcriptionId($subscription_id);
        }

        /**
         * Convertie une date UK en date FR
         */
        public function dateFormat($date) {
            $explode = explode('-', $date);

            return $explode[2].'/'.$explode[1].'/'.$explode[0];
        }
        
        /**
         * Cherche les info de connexion à la base de donnée pour dataTable
         *
         * @return information de connexion
         */
        public function getConnectionDatabase() {
            return $this->handler_gesco->pdo;
        }
    }
?>