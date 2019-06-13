<?php
namespace Ntic\Common\Plugin;

use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

/**
 * Class AddressPlugin
 * @package Ntic\Common\Plugin
 */
class AddressPlugin
{
    protected $objectManager;
    protected $logger;

    public function __construct(
        ResourceConnection $resourceConnection,
        LoggerInterface $logger
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
    }

    /**
     * Clear customer addresses from the quote_address table
     * M2 Bug: https://github.com/magento/magento2/issues/7570
     *
     * @param $subject
     * @param $proceed
     * @param $address
     */
    public function aroundDelete(
        \Magento\Customer\Model\ResourceModel\Address $subject,
        \Closure $proceed,
        $address)
    {
        try {
            $this->resourceConnection->getConnection()->fetchRow("
                UPDATE quote_address
                SET customer_address_id = NULL
                WHERE customer_address_id = '".$address->getId()."'
            ");
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        return $proceed($address);
    }
}