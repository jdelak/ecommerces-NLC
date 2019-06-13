<?php


namespace Ntic\PortfolioCustomer\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
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

        $table_ntic_portfolio_customer = $setup->getConnection()->newTable($setup->getTable('ntic_portfolio_customer'));


        $table_ntic_portfolio_customer->addColumn(
            'portfolio_customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'portfolio_id'
        );
        
        $table_ntic_portfolio_customer->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'identity' => false,'unsigned' => true,],
            'customer_id'

        );

        $table_ntic_portfolio_customer->addColumn(
            'seller_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'identity' => false,'unsigned' => true],
            'seller_id'
        );

        // CREATE FOREIGN KEY
        // CUSTOMER_ID
        $table_ntic_portfolio_customer->addIndex(
            $setup->getIdxName('ntic_portfolio_customer', ['customer_id']),
            ['customer_id']
        )
            ->addForeignKey(
                $setup->getFkName(
                    'ntic_portfolio_customer',
                    'customer_id', // champs store dans ntic_certif
                    'customer_entity',
                    'entity_id'
                ),
                'customer_id',
                $setup->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            );
        // CREATE FOREIGN KEY
        // SELLER_ID
        $table_ntic_portfolio_customer->addIndex(
            $setup->getIdxName('ntic_portfolio_customer', ['seller_id']),
            ['seller_id']
        )
            ->addForeignKey(
                $setup->getFkName(
                    'ntic_portfolio_customer',
                    'seller_id', // champs store dans ntic_certif
                    'customer_entity',
                    'entity_id'
                ),
                'seller_id',
                $setup->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            );

        $setup->getConnection()->createTable($table_ntic_portfolio_customer);

        $setup->endSetup();
    }
}
