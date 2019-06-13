<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.2
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Model\Group;

use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;
use Magento\Catalog\Model\Product as ProductModel;

class Product
{
    /**
     * Variable.
     *
     * @var CollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * Construct.
     *
     * @param CollectionFactory $groupCollectionFactory GroupCollectionFactory.
     */
    public function __construct(
        CollectionFactory $groupCollectionFactory
    ) {
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    /**
     * Get selected groups.
     *
     * @param ProductModel $product Products.
     *
     * @return selected groups
     */
    public function getSelectedGroups(ProductModel $product)
    {
        if (!$product->hasSelectedGroups()) {
            $groups = [];
            foreach ($this->getSelectedGroupsCollection($product) as $group) {
                $groups[] = $group;
            }
            $product->setSelectedGroups($groups);
        }
        return $product->getData('selected_groups');
    }

    /**
     * Get selected groups collection.
     *
     * @param ProductModel $product Products.
     *
     * @return selected groups
     */
    public function getSelectedGroupsCollection(ProductModel $product)
    {
        $collection = $this->groupCollectionFactory->create()
            ->addProductFilter($product);
        return $collection;
    }
}
