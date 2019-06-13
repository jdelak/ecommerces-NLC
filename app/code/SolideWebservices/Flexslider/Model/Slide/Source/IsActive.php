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

namespace SolideWebservices\Flexslider\Model\Slide\Source;

use SolideWebservices\Flexslider\Model\Slide;

/**
 * Class IsActive
 */
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Variable.
     *
     * @var \SolideWebservices\Flexslider\Model\Slide
     */
    protected $slide;

    /**
     * Construct.
     *
     * @param \SolideWebservices\Flexslider\Model\Slide $slide Slide.
     */
    public function __construct(Slide $slide)
    {
        $this->slide = $slide;
    }

    /**
     * Get options.
     *
     * @return $options[]
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->slide->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

}
