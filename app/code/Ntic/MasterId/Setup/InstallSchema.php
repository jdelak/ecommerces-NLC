<?php


namespace Ntic\MasterId\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
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

        $table_ntic_masterid_mastercode = $setup->getConnection()->newTable($setup->getTable('ntic_masterid_mastercode'));

        
        $table_ntic_masterid_mastercode->addColumn(
            'mastercode_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );
        

        
        $table_ntic_masterid_mastercode->addColumn(
            'nav_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true,'unsigned' => true],
            'nav_id'
        );
        

        
        $table_ntic_masterid_mastercode->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'identity' => false,'unsigned' => true, 'primary' => true],
            'customer_id'
        );
        

        
        $table_ntic_masterid_mastercode->addColumn(
            'well_master',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'well_master'
        );
        

        
        $table_ntic_masterid_mastercode->addColumn(
            'int_master',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'int_master'
        );

        // CREATE FOREIGN KEY
        $table_ntic_masterid_mastercode->addIndex(
            $setup->getIdxName('ntic_masterid_mastercode', ['customer_id']),
            ['customer_id']
        )
            ->addForeignKey(
                $setup->getFkName(
                    'ntic_masterid_mastercode',
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

        $setup->getConnection()->createTable($table_ntic_masterid_mastercode);

        $setup->endSetup();
    }
}
