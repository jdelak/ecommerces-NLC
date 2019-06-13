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

namespace SolideWebservices\Flexslider\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;

/**
 * Group Options for Flexslider Slides
 */
class GroupOptions implements OptionSourceInterface
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
     * @param CollectionFactory $groupCollectionFactory GroupCollection.
     */
    public function __construct(
        CollectionFactory $groupCollectionFactory
    ) {
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    /**
     * Get options.
     *
     * @return options[]
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];

        $options[] = [
            'value' => '',
            'label' => __('Select...'),
        ];

        $groupCollection = $this->groupCollectionFactory->create();
        foreach ($groupCollection as $group) {
            $options[] = [
                'value' => $group->getId(),
                'label' => $group->getTitle(),
            ];
        }

        return $options;
    }
}
