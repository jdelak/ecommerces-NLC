<?php


namespace Ntic\Subscription\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $table_ntic_subscription_contract = $setup->getConnection()->newTable($setup->getTable('ntic_subscription_contract'));

        
        $table_ntic_subscription_contract->addColumn(
            'contract_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true],
            'store_id'
        );


        
        $table_ntic_subscription_contract->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'unsigned' => true],
            'order_id'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'date_suscription',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
            null,
            ['nullable' => False],
            'date anniversaire'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'month_subscription',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => False],
            'mois abonnement'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'day_subscription',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => False],
            'jour abonnement'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'honored',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['nullable' => False],
            'abonnement payé'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'data_nav',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => False],
            'order envoyé à NAV en sérialisé'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'indice',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'nb tacite reconduction'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => False],
            'etat abonnement: TACITE RECONDUCTION/RESILIATION'
        );
        

        
        $table_ntic_subscription_contract->addColumn(
            'order_nav_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => False],
            'order_id renvoyé par NAV'
        );



        $table_ntic_subscription_contract->addIndex(
            $setup->getIdxName('ntic_subscription_contract', ['store_id']),
            ['store_id']
        )
            ->addForeignKey(
                $setup->getFkName(
                    'ntic_subscription_contract',
                    'store_id',
                    'store',
                    'store_id'
                ),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            );


        
        $table_ntic_subscription_contract->addIndex(
            $setup->getIdxName('ntic_subscription_contract', ['order_id']),
            ['order_id']
        )
            ->addForeignKey(
                $setup->getFkName(
                    'ntic_subscription_contract',
                    'order_id',
                    'sales_order',
                    'entity_id'
                ),
                'order_id',
                $setup->getTable('sales_order'),
                'entity_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            );



        $setup->getConnection()->createTable($table_ntic_subscription_contract);

        $setup->endSetup();
    }
}
