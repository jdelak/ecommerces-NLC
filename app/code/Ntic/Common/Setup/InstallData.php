<?php


namespace Ntic\Common\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;




class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    
    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'composition',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Composition',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'conseils_utilisation',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Conseils utilisation',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'contenance',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Contenance',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'resultat',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Resultat',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'analyse_nutritionnelle',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Analyse Nutritionnelle',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'duree_utilisation',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Duree Utilisation',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => 0,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'code_client',
            [
                'type' => 'varchar',
                'label' => 'Code Client',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
                'position' => 100,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' =>true,
                'is_searchable_in_grid' => true
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'mobile',
            [
                'type' => 'varchar',
                'label' => 'Mobile',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'system' => 2,
                'position' => 100
            ]
        );


    }

    
}
