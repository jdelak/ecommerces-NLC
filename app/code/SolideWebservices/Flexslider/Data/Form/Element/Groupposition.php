<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.1
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Data\Form\Element;

class Groupposition extends \Magento\Framework\Data\Form\Element\Select
{
    /**
     * Format an option as Html.
     *
     * @param array $option   Option.
     * @param array $selected Selected.
     *
     * @return string
     */
    protected function _optionToHtml($option, $selected)
    {
        if (is_array($option['value'])) {
            $html = '<optgroup label="' . $option['label'] . '">' . "\n";
            foreach ($option['value'] as $groupItem) {
                $html .= $this->_optionToHtml($groupItem, $selected);
            }
            $html .= '</optgroup>' . "\n";
        } else {
            $html = '<option value="' . $this->_escape($option['value']) . '"';
            $html .= isset($option['title']) ? 'title="' . $this->_escape($option['title']) . '"' : '';
            $html .= isset($option['style']) ? 'style="' . $option['style'] . '"' : '';
            if (in_array($option['value'], $selected)) {
                $html .= ' selected="selected"';
            }
            $html.= isset($option['disabled']) ? ' '.$this->_escape($option['disabled']) : '';
            $html .= '>' . $this->_escape($option['label']) . '</option>' . "\n";
        }
        return $html;
    }

}
