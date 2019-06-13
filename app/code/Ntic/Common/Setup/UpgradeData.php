<?php


namespace Ntic\Common\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
// ORDER
use Magento\Customer\Model\Customer;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;



class UpgradeData implements UpgradeDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    protected $categorySetupFactory;

    /**
     * Quote setup factory
     *
     * @var QuoteSetupFactory
     */
    protected $quoteSetupFactory;

    /**
     * Sales setup factory
     *
     * @var SalesSetupFactory
     */
    protected $salesSetupFactory;
    protected $customerSetupFactory;
    protected $attributeSetFactory;



    public function __construct(
        SalesSetupFactory $salesSetupFactory,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    )
    {
        $this->salesSetupFactory = $salesSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function upgrade( ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {
        if ( version_compare($context->getVersion(), '1.0.1', '<' )) {
            /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
            $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

            /**
             * Remove previous attributes
             */
            /*$attributes =       ['NEW_ATTRIBUTE'];
            foreach ($attributes as $attr_to_remove){
                $salesSetup->removeAttribute(\Magento\Sales\Model\Order::ENTITY,$attr_to_remove);

            }*/

            /**
             * Add 'NEW_ATTRIBUTE' attributes for order
             */
            $options = ['type' => 'varchar',
                        'visible' => true,
                        'required'   => false,
                        'searchable' => true,
                        'filterable' => true,
                        'comparable' => true];
            $salesSetup->addAttribute('order', 'Gesco_Order_Origin', $options);
            $salesSetup->addAttribute('order', 'NAV_Order_No', $options);

        }

        if ( version_compare($context->getVersion(), '1.0.3', '<' )) {

            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'code_client_gesco')
                ->addData([
                    'system' => 0,
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['customer_account_create', 'customer_account_edit', 'checkout_register'],
                ]);

            $attribute->save();

            $attribute2 = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'mobile')
                ->addData([
                    'system' => 0,
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['customer_account_create', 'customer_account_edit', 'checkout_register'],
                ]);

            $attribute2->save();
        }

        if(version_compare($context->getVersion(), '1.0.5', '<' )){
            /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');

            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            /**
             * Remove previous attributes
             */
            /*$attributes =       ['NEW_ATTRIBUTE'];
            foreach ($attributes as $attr_to_remove){
                $salesSetup->removeAttribute(\Magento\Sales\Model\Order::ENTITY,$attr_to_remove);

            }*/

            /**
             * Add 'NEW_ATTRIBUTE' attributes for order
             */
            $options = [
                'type' => 'varchar',
                'label' => 'NAV_Customer_No',
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
            ];
            $customerSetup->addAttribute('customer', 'NAV_Customer_No', $options);

            $attribute3 = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'NAV_Customer_No')
                ->addData([
                    'system' => 0,
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['customer_account_create', 'customer_account_edit', 'checkout_register'],
                ]);

            $attribute3->save();
        }

        if(version_compare($context->getVersion(), '1.0.6', '<' )){
            /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');

            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

            /**
             * Remove previous attributes
             */
            /*$attributes =       ['NEW_ATTRIBUTE'];
            foreach ($attributes as $attr_to_remove){
                $salesSetup->removeAttribute(\Magento\Sales\Model\Order::ENTITY,$attr_to_remove);

            }*/

            /**
             * Add 'NEW_ATTRIBUTE' attributes for order
             */
            $options = [
                'type' => 'varchar',
                'label' => 'NAV_Customer_No',
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
            ];
            $customerSetup->addAttribute('customer', 'NAV_Customer_No', $options);

            $attribute3 = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'NAV_Customer_No')
                ->addData([
                    'system' => 0,
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['customer_account_create', 'customer_account_edit', 'checkout_register'],
                ]);

            $attribute3->save();
        }

    }
}
?>