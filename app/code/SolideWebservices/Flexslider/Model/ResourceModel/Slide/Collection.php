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

namespace SolideWebservices\Flexslider\Model\ResourceModel\Slide;

use \SolideWebservices\Flexslider\Model\ResourceModel\AbstractCollection;

/**
 * Flexslider slide collection
 */
class Collection extends AbstractCollection
{
    /**
     * Variable.
     *
     * @var string
     */
    protected $_idFieldName = 'slide_id';

    /**
     * Define resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SolideWebservices\Flexslider\Model\Slide',
            'SolideWebservices\Flexslider\Model\ResourceModel\Slide');
        $this->_map['fields']['slide_id'] = 'main_table.slide_id';
    }

    /**
     * Returns pairs identifier - title for unique identifiers.
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
                $data['value'] .= '|' . $item->getData('slide_id');
            } else {
                $existingIdentifiers[] = $identifier;
            }

            $res[] = $data;
        }

        return $res;
    }

    /**
     * Add filter by store.
     *
     * @param int|array|\Magento\Store\Model\Store $store     Store.
     * @param bool                                 $withAdmin WithAdmin.
     *
     * @return void
     */
    public function addStoreFilter($store, $withAdmin = true)
    {

    }

    /**
     * Add filter by group.
     *
     * @param int $groupId GroupID.
     *
     * @return $this
     */
    public function addGroupFilter($groupId)
    {
        $this->performAddGroupFilter($groupId);
        return $this;
    }

    /**
     * Perform operations after collection load.
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoadSlide(
            'solidewebservices_flexslider_slide_group',
            'slide_id'
        );

        return parent::_afterLoad();
    }

    /**
     * Perform operations before rendering filters.
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->joinGroupRelationTable(
            'solidewebservices_flexslider_slide_group',
            'slide_id'
        );
    }

}
