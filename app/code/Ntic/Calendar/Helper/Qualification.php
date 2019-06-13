<?php

namespace Ntic\Calendar\Helper;
use \Ntic\PortfolioCustomer\Helper\Datatable\SHELPER;
/**
 * Class Qualification
 * @package Ntic\Calendar\Helper
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Qualification
{
    private $objectManager;
    private $connection;
    private $customerSession;

    function __construct()
    {
        // Instance OM (pas l'olympique => object manager)
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // Recuperation du modele SESSIONS
        $this->customerSession =  $this->objectManager->get('Magento\Customer\Model\Session');
        $this->connection = new \PDO('mysql:host=localhost;dbname=calendar_ntic','root','Kay8lWiBMQwu');

    }

    /**
     * Effectue la requete JOINTE sur la bonne base de données MAGENTO OU ContactMaster
     * afin de récupérer les infos de la personne selon qu'elle soit un contact ou un client
     * @return mixed
     */
    public function MarkedAsQualified()
    {
        $arrayOfres=[];
        // Recuperation de l'id customer connecter
        $customer_id = $this->customerSession->getData('customer_id');

        // Requête pour vérifier la provenance de la personne liée a l'event (contact ou client Magento)
        $isCustomerSql = "SELECT calendar_ntic.events.isCustomer,
                        calendar_ntic.events.event_id, 
                        calendar_ntic.events.contact,
                        calendar_ntic.events.end_date 
                        FROM calendar_ntic.events 
                        LEFT JOIN calendar_ntic.types ON calendar_ntic.events.type = calendar_ntic.types.typeid 
                        WHERE calendar_ntic.events.userId = " . $customer_id ."
                        AND calendar_ntic.events.end_date < '".date('Y-m-d H:i:s')."'
                        AND (calendar_ntic.events.qualif IS NULL 
                        OR calendar_ntic.events.qualif = 0 
                        OR calendar_ntic.events.qualif = 1) 
                        AND (calendar_ntic.types.typeid = 1 OR calendar_ntic.types.typeid = 3 OR calendar_ntic.types.typeid = 6 OR calendar_ntic.types.typeid = 8 
                        OR calendar_ntic.types.typeid = 21 OR calendar_ntic.types.typeid = 22)";

        $responseCustomer = $this->connection->query($isCustomerSql)->fetchAll(\PDO::FETCH_ASSOC);

        // Pour chaque evenements lié a l'utilisateur connecté
        foreach ($responseCustomer as $key => $item) {
            if ($item['isCustomer'] == 0) { // Si la personne est un contact
                $sql = "SELECT 
                        calendar_ntic.events.event_id, 
                        calendar_ntic.events.contact, 
                        CONCAT(contact_master.contact.lastname,' ', contact_master.contact.firstname) as name, 
                        calendar_ntic.events.qualif, 
                        calendar_ntic.events.end_date 
                        FROM calendar_ntic.events
                        LEFT JOIN calendar_ntic.types ON calendar_ntic.events.type = calendar_ntic.types.typeid 
                        LEFT JOIN contact_master.contact ON calendar_ntic.events.contact = contact_master.contact.id 
                        WHERE calendar_ntic.events.userId = ".$customer_id."
                        AND calendar_ntic.events.end_date < '".date('Y-m-d H:i:s')."'
                        AND (calendar_ntic.events.qualif IS NULL 
                        OR calendar_ntic.events.qualif = 0 
                        OR calendar_ntic.events.qualif = 1) 
                        AND (calendar_ntic.types.typeid = 1 OR calendar_ntic.types.typeid = 3 OR calendar_ntic.types.typeid = 6 OR calendar_ntic.types.typeid = 8 
                        OR calendar_ntic.types.typeid = 21 OR calendar_ntic.types.typeid = 22)";
            } else { // Si c'est un client Magento
                $sql = "SELECT 
                        calendar_ntic.events.event_id, 
                        calendar_ntic.events.contact, 
                        CONCAT(e_keops.customer_entity.lastname,' ', e_keops.customer_entity.firstname) as name, 
                        calendar_ntic.events.qualif, 
                        calendar_ntic.events.end_date 
                        FROM calendar_ntic.events
                        LEFT JOIN calendar_ntic.types ON calendar_ntic.events.type = calendar_ntic.types.typeid 
                        LEFT JOIN e_keops.customer_entity ON calendar_ntic.events.contact = e_keops.customer_entity.entity_id 
                        WHERE calendar_ntic.events.userId = ".$customer_id."
                        AND calendar_ntic.events.end_date < '".date('Y-m-d H:i:s')."' 
                        AND (calendar_ntic.events.qualif IS NULL 
                        OR calendar_ntic.events.qualif = 0 
                        OR calendar_ntic.events.qualif = 1 )
                        AND (calendar_ntic.types.typeid = 1 OR calendar_ntic.types.typeid = 3 OR calendar_ntic.types.typeid = 6 OR calendar_ntic.types.typeid = 8 
                        OR calendar_ntic.types.typeid = 21 OR calendar_ntic.types.typeid = 22)";
            }
            // On effectue la requete
            $res = $this->connection->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
            $arrayOfres[$key] = $res[$key]; // Sauvegarde des data dans un tableau qui recensera uniquement (et une seule fois) les bonnes informations de contact ou client
        }

        return  $arrayOfres;
    }

    public function getNotQualified()
    {
        return json_encode(array('data' => self::MarkedAsQualified()));
    }


}