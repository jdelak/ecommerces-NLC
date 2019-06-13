<?php


namespace Ntic\Subscription\Cron;
use Magento\Framework\App\ObjectManager as _objectManager;

class GetSubscriptionBirthdayCron
{

    protected $logger;
    protected $_resource;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resource
    ){
        $this->logger = $logger;
        $this->_resource = $resource;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        /**
         * Date du jour
         * On parse le mois et le jour de cette date
         */
        $nowDate = date('Y-m-d');
        $nowMonth = substr($nowDate, 3, 2);
        $nowDay = substr($nowDate, 0, 2);

        /**
         * Instanciate
         */
        $objectManager = _objectManager::getInstance(); // Instance de ObjectManager
        $connection  = $this->_resource->getConnection();
        /**
         * Récupère le model Ntic_Subscription_Contract Collection
         * Récupère le TableName
         * Effectue le select
         */
        //$collection = $objectManager->getCollection('Ntic\Subscription\Model\Contract'); // Collection
        $tableName   = $connection->getTableName('ntic_subscription_contract'); // Table ou l'on va inserer
        // Requete
        $sql = $connection->select()
            ->from($tableName);
        $result = $connection->fetchAll($sql);
        /**********************************************/
        /**             ***     ***                  **/
        /**                ** **                     **/
        /**                  *                       **/
        /**                                          **/
        /** ICI FOREACH SUR LE RESULT POUR LES DATES **/
        /**D'ANNIVERSAIRE ET LES TACITES RECONDUCTION**/
        /**                                          **/
        /**                  *                       **/
        /**                ** **                     **/
        /**             ***     ***                  **/
        /**********************************************/

        /**
         * Logger si tout est ok
         */
        $this->logger->addInfo("Cronjob getSubscriptionBirthdayCron is executed.");

    }
}