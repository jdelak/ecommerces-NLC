<?php


namespace Ntic\PayCustom\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ){
        $installer = $setup;

        $installer->startSetup();
        if (version_compare($context->getVersion(), "1.0.3", "<")) {
            $installer->getConnection()->addColumn(
                $installer->getTable('ntic_config_payment_fraction'),
                'store_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'comment' => 'Store id'
                ]
            );

        }

        if (version_compare($context->getVersion(), "1.0.5", "<")) {

            $quote = 'quote';
            $orderTable = 'sales_order';

            $installer->getConnection()->addColumn(
                $installer->getTable($quote),
                'seller_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'comment' => 'Seller id'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable($orderTable),
                'seller_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'comment' => 'Seller id'
                ]
            );

        }
        $installer->endSetup();
    }
}