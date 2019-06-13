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
 * Class GroupType
 */
class GroupType implements \Magento\Framework\Data\OptionSourceInterface
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
     * Group type options.
     *
     * @return $options[]
     */
    public function toOptionArray()
    {
        $options = [];

        $values =  [
            'basic'          => __('Basic slider'),
            'carousel'       => __('Carousel'),
            'basic-carousel' => __('Basic slider with carousel navigation'),
            'overlay'        => __('Basic slider with overlay navigation'),
        ];

        foreach ($values as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => __($label),
            ];
        }

        return $options;
    }

}
