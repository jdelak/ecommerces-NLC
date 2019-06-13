<?php


namespace Ntic\PayCustom\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
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

        $table_ntic_config_payment_abo = $setup->getConnection()->newTable($setup->getTable('ntic_config_payment_abo'));


        $table_ntic_config_payment_abo->addColumn(
            'config_payment_abo_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );



        $table_ntic_config_payment_abo->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false,'unsigned' => true],
            'identification de labonnement'
        );



        $table_ntic_config_payment_abo->addColumn(
            'type_mode_payment',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'CB_IBAN_ESPECE_CHEQUE'
        );



        $table_ntic_config_payment_abo->addColumn(
            'type_fraction',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            '1_2_3_4_5_6_12'
        );



        $table_ntic_config_payment_abo->addColumn(
            'first_echance_secure',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'CB_CHEQUE'
        );


        
        $table_ntic_config_payment_abo->addIndex(
            $setup->getIdxName('ntic_config_payment_abo', ['product_id']),
            ['product_id']
        )
            ->addForeignKey(
                $setup->getFkName(
                    'ntic_config_payment_abo',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $setup->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            );


        $table_ntic_config_payment_fraction = $setup->getConnection()->newTable($setup->getTable('ntic_config_payment_fraction'));


        $table_ntic_config_payment_fraction->addColumn(
            'config_payment_fraction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );



        $table_ntic_config_payment_fraction->addColumn(
            'amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
            null,
            [],
            'La somme des produits simples'
        );



        $table_ntic_config_payment_fraction->addColumn(
            'operateur',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            '< ou = ou <>'
        );



        $table_ntic_config_payment_fraction->addColumn(
            'fraction',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'fractions visibles 1_2'
        );



        $table_ntic_config_payment_fraction->addColumn(
            'to_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Simple'
        );



        $table_ntic_config_payment_fraction->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'product_id'
        );


        $setup->getConnection()->createTable($table_ntic_config_payment_fraction);

        $setup->getConnection()->createTable($table_ntic_config_payment_abo);

        $setup->endSetup();
    }
}
