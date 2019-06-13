<?php


namespace Ntic\AccessPermission\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;

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

        $table_ntic_accesspermission = $setup->getConnection()->newTable($setup->getTable('ntic_accesspermission'));

        
        $table_ntic_accesspermission->addColumn(
            'accesspermission_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );
        

        
        $table_ntic_accesspermission->addColumn(
            'group_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'group_id'
        );
        

        
        $table_ntic_accesspermission->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'store_id'
        );
        

        
        $table_ntic_accesspermission->addColumn(
            'rules',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'rules'
        );
        

        $setup->getConnection()->createTable($table_ntic_accesspermission);

        $setup->endSetup();
    }
}
