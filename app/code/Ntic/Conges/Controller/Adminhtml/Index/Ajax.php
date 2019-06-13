<?php

namespace Ntic\Conges\Controller\Adminhtml\Index;

class Ajax extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Mail\Temp
     */
    protected $_transportBuilder;

    protected $resultPageFactory;
    /**
     * @var \Magento\Store\Mode
     */
    protected $_storeManager;
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->request = $request;
        $this->resultPageFactory = $resultPageFactory;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;

        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /**
         * Si on clique sur les bouton dans datatable admin
         * On recupère les valeurs poster par Ajax
         */
        if ($this->request->isPost()) {
            self::updateConges();
        }
        //Instance de ObjectManager
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //Recupere la session pour connaitre l'utilisateur connecter
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        $customerid = $customerSession->getCustomer()->getId(); // Id utilisateur connecter

        // Informations de la base de données Magento
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $_db = $resource->getConnection(); // Connexion
        $tableName = $_db->getTableName('ntic_conges_conges'); // Table dur laquelle on requete principale
        $tableJoin = $_db->getTableName('customer_entity'); // Table sur laquelle on requete user

        // Requete pour récupérer tout les congès de l'utilisateur connecté
        $sql = "SELECT 	
                    ".$tableName.".conges_id,
                    ".$tableName.".start_at,
                    ".$tableName.".end_at,
                    ".$tableName.".demandeur_id,
                    ".$tableName.".event_id,
                    ".$tableName.".created_at,
                    ".$tableName.".updated_at,
                    ".$tableName.".comment,
                    ".$tableName.".type,
                    ".$tableName.".accepted,
                    CONCAT(".$tableJoin.".firstname,' ', ".$tableJoin.".lastname) AS name
                    FROM ".$tableName."
                    JOIN ".$tableJoin." ON ".$tableName.".demandeur_id= ".$tableJoin.".entity_id";
        // Execute la requete
        $data = $_db->fetchAll($sql);

        // Echo le résutlat au format Json pour l'exploitation par Datatable.js (view)
        echo json_encode(array('data' => $data));

    }

    public function updateConges()
    {
        //Instance de ObjectManager
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $conges_id = $this->request->getPost('conges_id'); // Id conges
        $action = $this->request->getPost('action'); // accept / refuse

        //Instance de ObjectManager
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // Informations de la base de données Magento

        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $_db = $resource->getConnection(); // Connexion
        $tableName = $_db->getTableName('ntic_conges_conges'); // Table dur laquelle on requete principale
        $tableJoin = $_db->getTableName('customer_entity'); // Table sur laquelle on requete user

        // Requete pour récupérer tout les congès de l'utilisateur connecté
        $sql = "SELECT 	
                    ".$tableName.".conges_id,
                    ".$tableName.".start_at,
                    ".$tableName.".end_at,
                    ".$tableName.".demandeur_id,
                    ".$tableName.".event_id,
                    ".$tableName.".created_at,
                    ".$tableName.".updated_at,
                    ".$tableName.".comment,
                    ".$tableName.".type,
                    ".$tableName.".accepted,
                    ".$tableName.".manager_validation,
                    ".$tableJoin.".email,
                    CONCAT(".$tableJoin.".firstname,' ', ".$tableJoin.".lastname) AS name
                    FROM ".$tableName."
                    JOIN ".$tableJoin." ON ".$tableName.".demandeur_id= ".$tableJoin.".entity_id
                    WHERE conges_id = ".$conges_id;

        // Execute la requete
        $userInfos = $_db->fetchAll($sql);

        if($action == 'refuse')
        {
            $accepted = false;
            // Requete pour récupérer tout les congès de l'utilisateur connecté
            $data = [
                'updated_at' => date("Y-m-d H:i:s"),
                'manager_validation' => $accepted,
                'accepted' => $accepted
            ];
        }
        else
        {
            $accepted = true;
            // Requete pour récupérer tout les congès de l'utilisateur connecté
            $data = [
                'updated_at' => date("Y-m-d H:i:s"),
                'manager_validation' => $accepted,
                'accepted' => $accepted
            ];
        }
        // Informations de la base de données Magento
        $resource = $objectManager ->get('Magento\Framework\App\ResourceConnection');
        $_db = $resource->getConnection(); // Connexion
        $tableName = $_db->getTableName('ntic_conges_conges'); // Table sur laquelle on requete

        $_db->update($tableName, $data, ['conges_id = ?' => $conges_id,]);

        self::sendResponseMail($userInfos); // On envoi le mail a la personne concernée

    }

    public function sendResponseMail($userInfos)
    {
        /**
         * Reponse a envoyé dans le mail
         * Si le manager refuse envoi le mail au demandeur avec unr reponse négative
         */
        if ($userInfos[0]['manager_validation'] == "0" )
        {
            $reponse = "Refusée";
            $mail = $userInfos[0]['email'];
            $templateMail = "reponse_cp";
            $message = "Réponse pour  votre demandede de congès ";

        }
        else // Envoie une demande de confirmation a La RH
        {
            $reponse = "Acccepté";
            $mail = "florian.magnan@lexel-paris.com"; // Mail RH
            $templateMail = "confirmation_Rh";
            $message = "La demande de congès de:";

        }
        /**
         * Envoi du mail de demande de congès a Laetitia avec détail de la demande
         * et lien de confirmation / refus (2 boutons)
         */
        $storeId = $this->_storeManager->getStore()->getId();
        $url = $this->_storeManager->getStore($storeId)->getUrl("conges/conges/edit");

        $templateVars = [
            "store"         => $this->_storeManager->getStore(),
            "customer_name" => $userInfos[0]['name'],
            "message"       => $message,
            "periode"       => "Pour la période du " .$userInfos[0]['start_at'] . ' au '. $userInfos[0]['end_at'].' inclus: à été '.$reponse,
        ];

        $transport = $this->_transportBuilder->setTemplateIdentifier($templateMail)
            ->setTemplateOptions( [ 'area' => \Magento\Framework\App\Area::AREA_ADMINHTML, 'store' => $this->_storeManager->getStore()->getId() ] )
            ->setTemplateVars( $templateVars )
            ->setFrom( [ "name" =>  'RH', "email" => 'laetitia@Rh.fr' ] )
            ->addTo($mail)
            ->setReplyTo( $mail )
            ->getTransport();

        $transport->sendMessage();
    }

}
