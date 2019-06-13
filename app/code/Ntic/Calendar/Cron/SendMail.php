<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ntic\Calendar\Cron;

class SendMail
{
    protected $_transportBuilder;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder

    ) {
        parent::__construct($context);
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->resource = $this->objectManager->get('Magento\Framework\App\ResourceConnection');
        $this->_transportBuilder = $transportBuilder;

        $this->_db = $this->resource->getConnection();

    }
    /**
     * Disable jobs which date is less than the current date
     *
     * @param \Magento\Cron\Model\Schedule $schedule
     * @return void
     */
    public function execute(\Magento\Cron\Model\Schedule $schedule)
    {


    }

    /**
     * A apeller dans le block de Calendar sur la methode test() pour debuger
     */
    public static function debug(){
        $event_db = new \PDO("mysql:host=localhost;dbname=calendar_ntic;charset=UTF8", "root", "Kay8lWiBMQwu");
        /**
         * Parcour tout les events de type rendez-vous
         */
        $checkMailingSql = "SELECT * FROM events 
                            WHERE (type = 1 OR type = 3 OR type = 6 OR type = 8 OR type = 21 OR type = 22) ";
        $event = $event_db->query($checkMailingSql)->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($event as $date ) { // Pour chacun des rendez-vous en base de données
            $sql = "SELECT DATEDIFF(DATE_FORMAT('". $date['start_date'] . "', '%Y-%m-%d'), '".date("Y-m-d")."' ) AS DIF"; // Difference entre aujourd'hui et jour du RDV
            $event_mail = $event_db->query($sql)->fetch(\PDO::FETCH_ASSOC);

            if ( $event_mail['DIF'] == 1 ) // On est à 1 jour de l'échéance du rendez-vous
            {
                $sqlSelectUser = "SELECT * FROM events WHERE  event_id = " . $date['event_id']; // On récupère l'id du contact et son statut (Client MAGE2 ou ContactMaster)
                $user_id = $event_db->query($sqlSelectUser)->fetch(\PDO::FETCH_ASSOC);
                if ( $user_id['isCustomer'] == 1 ) // Si c'est un customer magento Requete sur la base Client de magento pour recupérer les données du contact
                {
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                    $tableCustomer = $resource->getTableName('customer_entity');
                    $_db = $resource->getConnection();

                    $sql = " SELECT 
                    customer.entity_id as id,
                    CONCAT(customer.prefix, ' ', customer.lastname, ' ',customer.firstname) AS name,
                    customer.email,
                    address.city,
                    address.postcode as zip_code,
                    address.country_id as country,
                    address.telephone as tel1,
                    address.street as addr1
                     FROM ".$tableCustomer." as customer
                     LEFT JOIN customer_address_entity as address ON customer.entity_id = address.parent_id
                     WHERE customer.entity_id = ". $user_id['contact'];

                   $resultat = $_db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

                } else { // Requete sur contact master
                    $contact_master_db = new \PDO("mysql:host=localhost;dbname=contact_master;charset=UTF8", "root", "Kay8lWiBMQwu");
                    $contact_master_sql = "SELECT CONCAT(prefix, ' ', lastname, ' ', firstname ),  AS name, email FROM contact WHERE id = ". $user_id['contact'];
                    $resultat = $contact_master_db->query($contact_master_sql)->fetch(\PDO::FETCH_ASSOC);

                }
                var_dump($resultat);
              /*
               * Envoi des mails au personnes ayant un rendez vous le lendemain
               * $resultat contient les infos a passer au template email pout l'envoi a l'adresse mail $resultat['email']
               */


            }
        }
    }

    /**
     * Envoi du Mail
     */
    public function sendMail()
    {
        $reponse = "Acccepté";
        $mail = "florian.magnan@lexel-paris.com"; // Mail RH
        $templateMail = "confirmation_Rh";
        $message = "La demande de ".$demande['type']." de : ";


        /**
         *
         */
        $storeId = $this->_storeManager->getStore()->getId();
        $url = $this->_storeManager->getStore($storeId)->getUrl("conges/conges/confirmation/");// Url pour la validation des conges

        $templateVars = [
            "store"         => $this->_storeManager->getStore(),
            "customer_name" => $userInfos[0]['firstname'].' '. $userInfos[0]['lastname'],
            "message"       => $message,
            "periode"       => "Pour la période du " .$debut . ' au '. $fin.' inclus: à été '.$reponse,
            "url"           => $url,
            "token"         => $demande['token'],
            "lastcongesId" => $demande['conges_id']
        ];

        $transport = $this->_transportBuilder->setTemplateIdentifier($templateMail)
            ->setTemplateOptions( [ 'area' => \Magento\Framework\App\Area::AREA_ADMINHTML, 'store' => $this->_storeManager->getStore()->getId() ] )
            ->setTemplateVars( $templateVars )
            ->setFrom( $setFrom )
            ->addTo($mail)
            ->setReplyTo( $mail )
            ->getTransport();

        $transport->sendMessage();
    }

}