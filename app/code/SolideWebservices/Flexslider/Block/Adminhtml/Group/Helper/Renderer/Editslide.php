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

namespace SolideWebservices\Flexslider\Block\Adminhtml\Group\Helper\Renderer;

use Magento\Backend\Block\Context;
use SolideWebservices\Flexslider\Model\SlideFactory;
use Magento\Framework\Registry;

/**
 * Flexslider Editslide Helper
 */
class Editslide extends
\Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Variable.
     *
     * @var \SolideWebservices\Flexslider\Model\SlideFactory
     */
    protected $_slideFactory;

    /**
     * Variable.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Construct.
     *
     * @param Context      $context      Context.
     * @param SlideFactory $slideFactory SlideFactory.
     * @param Registry     $coreRegistry CoreRegistry.
     * @param array        $data         Data.
     */
    public function __construct(
        Context $context,
        SlideFactory $slideFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_slideFactory = $slideFactory;
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * Render row.
     *
     * @param \Magento\Framework\DataObject $row Row.
     *
     * @return grid edit url
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        return '<a href="' . $this->getUrl(
                                        '*/slide/edit',
                                        [
                                        '_current' => false,
                                        'slide_id' => $row->getId()
                                        ]
                                    ) . '" target="_blank">Edit</a>';
    }
}
