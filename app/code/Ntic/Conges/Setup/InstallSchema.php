<?php


namespace Ntic\Conges\Setup;

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

        $table_ntic_conges_conges = $setup->getConnection()->newTable($setup->getTable('ntic_conges_conges'));


        $table_ntic_conges_conges->addColumn(
            'conges_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'auto_increment' => true,'unsigned' => true),
            'Entity ID'
        );


        $table_ntic_conges_conges->addColumn(
            'start_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
            null,
            ['nullable' => False],
            'date de début des congès'
        );



        $table_ntic_conges_conges->addColumn(
            'end_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
            null,
            ['nullable' => False],
            'date de fin des congès'
        );



        $table_ntic_conges_conges->addColumn(
            'demandeur_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False,'identity' => false,'unsigned' => true],
            'id_demandeur'
        );

        //CLE ETRANGERE SUR MAGENTO CUSTOMER
        $table_ntic_conges_conges->addIndex(
            $setup->getIdxName('ntic_conges', ['demandeur_id']),
            ['demandeur_id']
        )
            ->addForeignKey(
                $setup->getFkName(
                    'ntic_conges',
                    'demandeur_id', // champs store dans ntic_certif
                    'customer_entity',
                    'entity_id'
                ),
                'demandeur_id',
                $setup->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            );


        $table_ntic_conges_conges->addColumn(
            'event_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'id evenement scheduler'
        );


        $table_ntic_conges_conges->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => False],
            'date de création de la demande'
        );



        $table_ntic_conges_conges->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['nullable' => False],
            'updated_at'
        );


        $table_ntic_conges_conges->addColumn(
            'comment',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => True],
            'commentaire'
        );

        $table_ntic_conges_conges->addColumn(
            'type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            100,
            ['nullable' => False],
            'Type de congès'
        );


        $table_ntic_conges_conges->addColumn(
            'accepted',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['default' => false,'nullable' => False,'identity' => false],
            'Accepté/refusé'
        );


        $setup->getConnection()->createTable($table_ntic_conges_conges);

        $setup->endSetup();
    }
}
