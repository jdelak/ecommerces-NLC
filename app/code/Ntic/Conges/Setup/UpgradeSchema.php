<?php


namespace Ntic\Conges\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $table_ntic_conges_conges = $setup->getTable('ntic_conges_conges');

        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $setup->getConnection()->addColumn($table_ntic_conges_conges, 'Token pour validation',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length'    => 255,
                    'nullable' => false,
                    'default' => '0',
                    'comment' => 'Tohen pour validation'
                ]);
        }

        if (version_compare($context->getVersion(), "1.0.2", "<")) {
            $setup->getConnection()->changeColumn(
                $setup->getTable('ntic_conges_conges'),
                'Token pour validation',
                'token',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length'    => 255,
                    'nullable' => false,
                    'default' => '0',
                    'comment' => 'Token pour validation'
                ]
            );
        }

        if (version_compare($context->getVersion(), "1.0.4", "<")) {
            $setup->getConnection()->addColumn($table_ntic_conges_conges, 'manager_validation',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    'nullable' => false,
                    'default' => false,
                    'comment' => 'Avis du manager'
                ]);
        }

        $setup->endSetup();
    }
}
