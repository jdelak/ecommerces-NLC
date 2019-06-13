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

namespace SolideWebservices\Flexslider\Model\ResourceModel\Group;

use \SolideWebservices\Flexslider\Model\ResourceModel\AbstractCollection;

/**
 * Flexslider group collection
 */
class Collection extends AbstractCollection
{
    /**
     * Variable.
     *
     * @var string
     */
    protected $_idFieldName = 'group_id';

    /**
     * Load data for preview flag.
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Define resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SolideWebservices\Flexslider\Model\Group',
                    'SolideWebservices\Flexslider\Model\ResourceModel\Group');
        $this->_map['fields']['group_id'] = 'main_table.group_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Returns pairs identifier - title for unique identifiers
     * and pairs identifier|group_id - title for non-unique after first.
     *
     * @return $res[]
     */
    public function toOptionIdArray()
    {
        $res = [];
        $existingIdentifiers = [];
        foreach ($this as $item) {
            $identifier = $item->getData('identifier');

            $data['value'] = $identifier;
            $data['label'] = $item->getData('title');

            if (in_array($identifier, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData('group_id');
            } else {
                $existingIdentifiers[] = $identifier;
            }

            $res[] = $data;
        }

        return $res;
    }

    /**
     * Set first store flag.
     *
     * @param bool $flag Flag.
     *
     * @return $this
     */
    public function setFirstStoreFlag($flag = false)
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    /**
     * Add filter by store.
     *
     * @param int|array|\Magento\Store\Model\Store $store     Store.
     * @param bool                                 $withAdmin WithAdmin.
     *
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * Add filter by page.
     *
     * @param int $groupId GroupID.
     *
     * @return $this
     */
    public function addPageFilter($groupId)
    {
        $this->performAddPageFilter($groupId);
        return $this;
    }

    /**
     * Add filter by category.
     *
     * @param int $groupId GroupID.
     *
     * @return $this
     */
    public function addCategoryFilter($groupId)
    {
        $this->performAddCategoryFilter($groupId);
        return $this;
    }

    /**
     * Add filter by product.
     *
     * @param int $groupId GroupID.
     *
     * @return $this
     */
    public function addProductFilter($groupId)
    {
        $this->performAddProductFilter($groupId);
        return $this;
    }

    /**
     * Add filter by slide.
     *
     * @param int $slideId SlideID.
     *
     * @return $this
     */
    public function addSlideFilter($slideId)
    {
        $this->performAddSlideFilter($slideId);
        return $this;
    }

    /**
     * Perform operations after collection load.
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoadGroup(
            'solidewebservices_flexslider_store',
            'group_id'
        );
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }

    /**
     * Perform operations before rendering filters.
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->joinStoreRelationTable(
            'solidewebservices_flexslider_store',
            'group_id'
        );
        $this->joinRelationTable(
            'solidewebservices_flexslider_page',
            'group_id'
        );
        $this->joinRelationTable(
            'solidewebservices_flexslider_category',
            'group_id'
        );
        $this->joinRelationTable(
            'solidewebservices_flexslider_product',
            'group_id'
        );
    }

}
