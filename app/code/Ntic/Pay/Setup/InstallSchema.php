<?php


namespace Ntic\Pay\Setup;

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

        $table_ntic_certif = $setup->getConnection()->newTable($setup->getTable('ntic_certif'));


        $table_ntic_certif->addColumn(
            'certif_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );



        $table_ntic_certif->addColumn(
            'shopId',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => False],
            'shopId'
        );



        $table_ntic_certif->addColumn(
            'certTest',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => False],
            'certTest'
        );



        $table_ntic_certif->addColumn(
            'certProd',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => False],
            'certProd'
        );



        $table_ntic_certif->addColumn(
            'ctxMode',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => False],
            'ctxMode'
        );



        $table_ntic_certif->addColumn(
            'wsdl',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => False],
            'wsdl'
        );



        $table_ntic_certif->addColumn(
            'ns',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => False],
            'ns'
        );



        $table_ntic_certif->addColumn(
            'store',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => False,'unsigned' => true],
            'store'
        );

        $table_ntic_certif->addIndex(
            $setup->getIdxName('ntic_certif', ['store']),
            ['store']
        )
            ->addForeignKey(
            $setup->getFkName(
                'ntic_certif',
                'store', // champs store dans ntic_certif
                'store', //table Store
                'store_id'
            ),
            'store',
            $setup->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE,
            Table::ACTION_CASCADE
        );

        $setup->getConnection()->createTable($table_ntic_certif);

        $setup->endSetup();
    }
}
