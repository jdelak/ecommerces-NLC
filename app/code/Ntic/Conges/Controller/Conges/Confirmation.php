<?php
namespace Ntic\Conges\Controller\Conges;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Confirmation extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Store\Model
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\Mail\Temp
     */
    protected $_transportBuilder;
    protected $scopeConfig;
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;

        $this->_transportBuilder = $transportBuilder;

        parent::__construct($context);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /**
         * A utiliser lorsque l'email Rh sera configurer dans l'admin MAGENTO
         * $rhEmail = $this->scopeConfig->getValue('trans_email/ident_support/email',ScopeInterface::SCOPE_STORE);
         */

        // ObjectManager pour la connexion a la base de données
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager ->get('Magento\Framework\App\ResourceConnection');
        $_db = $resource->getConnection();
        $tableName = $_db->getTableName('ntic_conges_conges'); // Table sur laquelle on requete


        $storeId = $this->_storeManager->getStore()->getId();
        $url = $this->_storeManager->getStore($storeId)->getUrl("conges/conges/confirmation/");// Url pour la validation des conges

        $conges = $_db->getTableName('ntic_conges_conges');

        $token = $this->getRequest()->getParam('token');
        $id = $this->getRequest()->getParam('id');
        $action = $this->getRequest()->getParam('action');
        $rh = $this->getRequest()->getParam('rh');

        // Requete pour trouver la demande de congès grace au token
        $sql = $_db->select()
        ->from($conges)
        ->where("ntic_conges_conges.token = '" . $token . "'");

        $demande = $_db->query($sql)->fetch(); // Resultat

        if ($demande == true)
        {
            // Requete pour les infos du demandeur des congés
            $sqlCustomerInfos = " SELECT * FROM customer_entity
                              WHERE entity_id = " . $demande['demandeur_id'];
            $userInfos = $_db->query($sqlCustomerInfos)->fetchAll();

            if ($rh == 'false') // Manager
            {
                if($action == '0') // Refus
                {
                    $accepted = false;
                    // Requete pour récupérer tout les congès de l'utilisateur connecté
                    $data = [
                        'updated_at' => date("Y-m-d H:i:s"),
                        'manager_validation' => $accepted,
                        'accepted' => $accepted
                    ];
                    $this->messageManager->addSuccess( __('Refus envoyé a '.$userInfos[0]['firstname'].' '.$userInfos[0]['lastname'] ) );
                }
                else // Acceptation
                {
                    $newtoken = sha1(uniqid()); // Reecrit le token pour la RH
                    $accepted = true;
                    $data = [
                        'updated_at' => date("Y-m-d H:i:s"),
                        'manager_validation' => $accepted,
                        'token' => $newtoken
                    ];

                    $this->messageManager->addSuccess( __('Demande transmise aux Ressources Humaines' ) );
                }

            } else // Rh
            {
                if($action == '0')
                {
                    $accepted = false;
                    $data = [
                        'updated_at' => date("Y-m-d H:i:s"),
                        'manager_validation' => $accepted,
                        'accepted' => $accepted
                    ];
                    $this->messageManager->addSuccess( __('Refus envoyé à '.$userInfos[0]['firstname'].' '.$userInfos[0]['lastname'] ) );
                } else
                {
                    $accepted = true;
                    // Requete pour récupérer tout les congès de l'utilisateur connecté
                    $data = [
                        'updated_at' => date("Y-m-d H:i:s"),
                        'accepted' => $accepted
                    ];
                    $this->messageManager->addSuccess( __('Acceptation envoyé à '.$userInfos[0]['firstname'].' '.$userInfos[0]['lastname'] ) );

                }
            }

            $updated = $_db->update($tableName, $data, ['conges_id = ?' => $id,]); // Met a jour le a demande

            if($updated == true ) // requete pour recupérer après update
            {
                // Requete pour trouver la demande de congès grace au token
                $sql = $_db->select()
                    ->from($conges)
                    ->where("ntic_conges_conges.conges_id = '" . $id . "'");

                $demande = $_db->query($sql)->fetch(); // Resultat
                self::sendResponseMail($demande, $userInfos, $rh, $_db); // On envoi le mail a la personne concernée
            }
        }

        $this->_view->loadLayout();
        $this->_view->renderLayout();

    }

    /**
     * Permet l'envoi du mail
     * @param $demande resultat de la requete du execute demande de conges
     * @param $userInfos resultat de la requete du execute sur les infos de l'utilisateur concerné par la demande de Congès
     * @param $rh boolean si avis des ressources humaine ou pas (false = manager)
     * @param $_db
     */
    public function sendResponseMail($demande, $userInfos, $rh, $_db)
    {
        $tableName = $_db->getTableName('ntic_conges_conges'); // Table sur laquelle on requete
        $id = $this->getRequest()->getParam('id');

        /**
         * Formatage rapide affichage date au format francais
         */
        $debut = join( '-', array_reverse( explode("-", $demande['start_at']) ) );
        $fin = join( '-', array_reverse( explode("-", $demande['end_at']) ) );

        if ($rh == 'true' ) // RH
        {
            $setFrom = [ "name" =>  'RH', "email" => 'laetitia@Rh.fr' ];
            if ($demande['accepted'] == "0") // Si refuser mail a la personne demandeuse
            {
                $reponse = "Refusée";
                $mail = $userInfos[0]['email'];
                $templateMail = "reponse_cp";
                $message = "Réponse pour votre demande de " .$demande['type'];
                $this->deleteConges($demande['event_id']);
            }
            else // Si accepté mail a la personne demandeuse
            {
                $reponse = "Acccepté";
                $mail = $userInfos[0]['email']; // Mail de la personne
                $templateMail = "reponse_cp";
                $message = "Votre demande de " . $demande['type'];
            }
            /**
             * Descision finale par la RH on delete le TOKEN
             */
            $data = [
                'updated_at' => date("Y-m-d H:i:s"),
                'token' => null
            ];
           $_db->update($tableName, $data, ['conges_id = ?' => $id,]); // Met a jour le a demande

        }
        else // MANAGER
        {
            $setFrom = [ "name" => 'Manager', "email" => 'laetitia@manager.fr' ]; // MAIL DU MANAGER

            if ($demande['manager_validation'] == "0" ) // ENvoie mail de refus au demandeur
            {
                $reponse = "Refusée";
                $mail = $userInfos[0]['email'];
                $templateMail = "reponse_cp";
                $message = "Votre demande de " .$demande['type'];
                /**
                 * Descision finale par la RH on delete le TOKEN
                 */
                $data = [
                    'updated_at' => date("Y-m-d H:i:s"),
                    'token' => null
                ];
                $_db->update($tableName, $data, ['conges_id = ?' => $id,]); // Met a jour le a demande
                $this->deleteConges($demande['event_id']);

            }
            else // Envoie une demande de confirmation a La RH
            {
                $reponse = "Acccepté";
                $mail = "florian.magnan@lexel-paris.com"; // Mail RH
                $templateMail = "confirmation_Rh";
                $message = "La demande de ".$demande['type']." de : ";

            }

        }

        /**
         * Envoi du mail de demande de congès a Laetitia avec détail de la demande
         * et lien de confirmation / refus (2 boutons)
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

    /**
     * Efface les congès enregistrés dans calendar_ntic
     * @param $id
     */
    public function deleteConges($id)
    {
        /**
         * DELETE calendar_ntic
         */
        $calendar_connect = new \PDO('mysql:host=localhost;dbname=calendar_ntic','root','Kay8lWiBMQwu');
        $sqlCalendar = "DELETE FROM events WHERE event_id = :eventId";
        $stmt = $calendar_connect->prepare($sqlCalendar);
        $stmt->execute(array(
            ':eventId' => $id
        ));
    }


}
