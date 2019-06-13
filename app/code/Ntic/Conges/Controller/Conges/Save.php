<?php

namespace Ntic\Conges\Controller\Conges;

error_reporting(E_ALL);
ini_set('display_errors', 1);
class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    )
    {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
    }

    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager ->get('Magento\Framework\App\ResourceConnection');
        $_db = $resource->getConnection();
        $tableName = $_db->getTableName('ntic_conges_conges');

        $values = $this->getRequest()->getPostValue();//Récupère les données envoyées
        /**
         * Formatage rapide affichage date au format francais
         */
        $debut = join( '-', array_reverse( explode("-", $values['start_at']) ) );
        $fin = join( '-', array_reverse( explode("-", $values['end_at']) ) );
        if ($values['start_at'] >= $values['end_at'] ) {
            /**
             * Redirection avec message d'erreur
             */
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addError( __('La date de fin de congès est antérieur à la date de début ! ') );
            $customerBeforeAuthUrl = $this->_url->getUrl('conges/');

            return $resultRedirect->setPath($customerBeforeAuthUrl);
        }

        /**
         * Instanciation du block
         */
        $layout = $this->_view->getLayout();
        $block = $layout->createBlock('Ntic\Conges\Block\Setconges');
        $conges = $block->checkConges($values['start_at'], $values['end_at']); // Vérification de la superposition des congès et des évenements prévus

        if ($conges != false) // Si il y a des events sur les congès demandés alors on redirige avec un message
        {
            /**
             * Redirection avec message d'erreur
             */
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addError( __('Vous avez des rendez-vous prévus sur la date demandée ( du '.$debut.' au ' .$fin.') : Veuillez organiser vôtre agenda ou contacter votre Binôme. <a class="pull-right" href=" '.$this->_url->getUrl('calendar/').' "> <i class="fa fa-address-book-o" aria-hidden="true"></i> Votre Agenda</a>' ) );
            $customerBeforeAuthUrl = $this->_url->getUrl('conges/');

            return $resultRedirect->setPath($customerBeforeAuthUrl);

        }

        $token = sha1(uniqid());// Création du token

        $sqlCustomerInfos = " SELECT * FROM customer_entity
                              WHERE entity_id = " . $values['demandeur_id'];

        $userInfos = $_db->query($sqlCustomerInfos)->fetchAll();
        $sql = "INSERT INTO " . $tableName . " (
                                                start_at,
                                                end_at,
                                                demandeur_id,
                                                event_id,
                                                created_at,
                                                updated_at,
                                                comment,
                                                type,
                                                accepted,
                                                token
                                                )
                                                Values
                                                (
                                                '" . $values['start_at']. "',
                                                '" . $values['end_at']. "',
                                                '" . $values['demandeur_id']. "',
                                                '',
                                                '".date("Y-m-d H:i:s")."',
                                                '',
                                                '',
                                                '".$values['type']."',
                                                 '0',
                                                 '".$token."'
                                                )";
        $result = $_db->query($sql);
        $lastcongesId = $_db->lastInsertId($tableName); // Récupère le dernier enregistrement dans la table Congès

        /**
         * INSERT calendar_ntic lors de la demande de conges
         * Met à jour la demande de conge (ntic_conges_conges) avec l'id de calendar_ntic
         */
        $sqlStatus = $_db->select()
            ->from($tableName)
            ->where("ntic_conges_conges.conges_id = '" . $lastcongesId . "'");
        $derniereDemandeCp = $_db->query($sqlStatus)->fetch(); // Resultat de la demande

        if($derniereDemandeCp != false) // Si demande se passe bien
        {
            $calendar_connect = new \PDO('mysql:host=localhost;dbname=calendar_ntic','root','Kay8lWiBMQwu');
            $sqlCalendar = "INSERT INTO events (start_date, end_date, type, userId) VALUES (:start_cp, :end_cp, :type_cp, :uId)";
            $stmt = $calendar_connect->prepare($sqlCalendar);
            $stmt->execute(array(
                ':start_cp' => $derniereDemandeCp['start_at']. ' 00:00:00',
                ':end_cp' =>  $derniereDemandeCp['end_at']. ' 23:59:00',
                ':type_cp' => '5', // Conges
                ':uId' => $derniereDemandeCp['demandeur_id']
            ));

            $lastCalendarId =  $calendar_connect->lastInsertId('calendar_ntic');

            $data = [
                'event_id' => $lastCalendarId
            ];
            $_db->update($tableName, $data, ['conges_id = ?' => $derniereDemandeCp['conges_id'],]); // Met conges_conges_ntic avec l'id calendar_ntic events
        }
        /**
         * Fin INSERT calendar_ntic lors de la demande de conges
         */

        if( $result == true ) // Si la requète s'effectue correctement dans la table conges_conges_ntic
        {
            /**
             * Envoi du mail de demande de congès a Laetitia avec détail de la demande
             * et lien de confirmation / refus (2 boutons)
             */
            $storeId = $this->_storeManager->getStore()->getId();
            $url = $this->_storeManager->getStore($storeId)->getUrl("conges/conges/confirmation/");
            $templateVars = [
                "store"         => $this->_storeManager->getStore(),
                "customer_name" => $userInfos[0]['firstname']. ' ' .$userInfos[0]['lastname'],
                "message"       => "Une nouvelle demande de ".$values['type']." a été effectuer par ",
                "periode"       => "pour la période du " .$debut . ' au '. $fin.' inclus',
                "lastcongesId"  =>  $lastcongesId,
                "url"           =>  $url,
                "token"         =>  $token
            ];

            $transport = $this->_transportBuilder->setTemplateIdentifier("demande_cp")
                ->setTemplateOptions( [ 'area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->_storeManager->getStore()->getId() ] )
                ->setTemplateVars( $templateVars )
                ->setFrom( [ "name" =>  $userInfos[0]['firstname']. ' ' .$userInfos[0]['lastname'], "email" => $userInfos[0]['email'] ] )
                ->addTo('florian.magnan@lexel-paris.com')// Mail de laetitia
                ->setReplyTo( $userInfos[0]['email'] )
                ->getTransport();

            $transport->sendMessage();

            /**
             * Redirection avec message de réussite
             */
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addSuccess( __('Votre demande de '.$values['type'].' a bien été envoyé vous recevrez un mail lors de la decision de la direction') );
            $customerBeforeAuthUrl = $this->_url->getUrl('conges/');

            return $resultRedirect->setPath($customerBeforeAuthUrl);
        } else {
            /**
             * Redirection avec message d'erreur
             */
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addError( __('Une erreur est survenue lors de la demande') );
            $customerBeforeAuthUrl = $this->_url->getUrl('conges/');

            return $resultRedirect->setPath($customerBeforeAuthUrl);
        }


    }
}
