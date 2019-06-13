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

namespace SolideWebservices\Flexslider\Block\Adminhtml\Slide\Helper;

use Magento\Framework\Data\Form\Element\Factory as ElementFactory;
use Magento\Framework\Data\Form\Element\CollectionFactory as ElementColFactory;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use SolideWebservices\Flexslider\Helper\Image as ImageHelper;

/**
 * Flexslider Slide Image Helper
 */
class Image extends \Magento\Framework\Data\Form\Element\Image
{
    /**
     * Variable.
     *
     * @var Image
     */
    protected $_imageHelper;

    /**
     * Construct.
     *
     * @param ElementFactory    $factoryElement    FactoryElement.
     * @param ElementColFactory $factoryCollection FactoryCollection.
     * @param Escaper           $escaper           Escaper.
     * @param UrlInterface      $urlBuilder        URLBuilder.
     * @param ImageHelper       $imageHelper       ImageHelper.
     * @param array             $data              Data.
     */
    public function __construct(
        ElementFactory $factoryElement,
        ElementColFactory $factoryCollection,
        Escaper $escaper,
        UrlInterface $urlBuilder,
        ImageHelper $imageHelper,
        $data = []
    ) {
        $this->_imageHelper = $imageHelper;
        parent::__construct(
            $factoryElement,
            $factoryCollection,
            $escaper,
            $urlBuilder,
            $data
        );
    }

    /**
     * Get thumbnail URL.
     *
     * @return thumbnail image url
     */
    protected function _getUrl()
    {
        if ($this->getValue() && !is_array($this->getValue())) {
            return $this->_imageHelper->getImageUrl($this->getValue(),
            \SolideWebservices\Flexslider\Model\Slide::THUMB_MEDIA_PATH);
        }
        return null;
    }
}
