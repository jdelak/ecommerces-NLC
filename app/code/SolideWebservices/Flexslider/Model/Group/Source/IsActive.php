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

namespace SolideWebservices\Flexslider\Model\Group\Source;

use SolideWebservices\Flexslider\Model\Group;

/**
 * Class IsActive
 */
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Variable.
     *
     * @var \SolideWebservices\Flexslider\Model\Group
     */
    protected $group;

    /**
     * Construct.
     *
     * @param \SolideWebservices\Flexslider\Model\Group $group Group.
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get options.
     *
     * @return $options[]
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->group->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

}
