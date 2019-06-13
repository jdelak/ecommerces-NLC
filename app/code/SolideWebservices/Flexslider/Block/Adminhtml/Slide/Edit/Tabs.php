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

namespace SolideWebservices\Flexslider\Block\Adminhtml\Slide\Edit;

/**
 * Admin flexslider slide left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('slide_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Slide Settings'));
    }

}
