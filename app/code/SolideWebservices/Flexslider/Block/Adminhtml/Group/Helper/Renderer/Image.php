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
use Magento\Store\Model\StoreManagerInterface;
use SolideWebservices\Flexslider\Model\SlideFactory;
use Magento\Framework\Registry;

/**
 * Flexslider Editslide Helper
 */
class Image extends
\Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Variable.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

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
     * @param Context               $context      Context.
     * @param StoreManagerInterface $storeManager StoreManager.
     * @param SlideFactory          $slideFactory SlideFactory.
     * @param Registry              $coreRegistry CoreRegistry.
     * @param array                 $data         Data.
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        SlideFactory $slideFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->_slideFactory = $slideFactory;
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * Render row.
     *
     * @param \Magento\Framework\DataObject $row Row.
     *
     * @return thumbnail image url
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $slide = $this->_slideFactory->create()->load($row->getId());

        if ($slide->getImage()) {
            $srcImage = $this->_storeManager
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                . 'flexslider/thumbnails/' . $slide
                ->getImage();
            return '<image src="' . $srcImage . '" alt="' . $slide->getAltText()
             . '" style="max-width: 100px;">';
        } else {
            return '<image src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA
            C8AAAAfCAYAAAB3XZQBAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEA
            mpwYAAAAB3RJTUUH4AEeEwwPdVz3igAAAhpJREFUWMPtl89q20AQxr+RVm5ASCg6pBg
            bDL7UcU51bg741rxBcg55rNCXkI8pcdwHSKGlJ0NMihWDQywhMFWslXd7aK3GDW6p/9
            QK1YAOu4fl983Ozjeij58+SzzTUPCMI4PP4DP4/xmeiKCqKogIirLe3LBlD5BSgogSc
            M/z0Ww2wTnHwUEd1WoVUsp0wUsp0W6/R7/fh2maODx8AyklHMdBHMcAgMvLNgzDRLFY
            SFfZ+L6PbreLMAwxGAzQ6/UwHo8hhPiZGcbgum5yM6mBV1V1Zq1pGnRdn6lzzjl2dyt
            rKxtadLYhInQ6HdzcfIFt29jfr0FVVQRBgKurD+Cco1J5hVKphMlkMiNaCLESQbTMYH
            Z93UUUjX+8AQCQiTAAEEIgn8/Dtu1kfXHRwtbWCzQajaUFLPxg7++HaLVaCUC5XMbOz
            stEwDQGgztYloUgCHB+/g6+72Nvr7rpbiNAREmW45jj4eErfk2mEBMoigLHaSKO45X2
            fraqg1z3Fq57+2Rf13UUCkWEYQhN09LlsFJKRFE0t37X1CVXA2+aJk5PT7C9bf1OYjr
            hGWNgjEFR1Dk3813gY/NKDbzneTg7e4vhcDi3rDRNQ71eX7mAheENw0Aul0vcdQr6+B
            NCwLZtEBFqtdc4Pj564swbMynG2B/nFiFE4rBEhNFoBM45LMvabKucTo9/05l0Xc/+p
            DL4DD6Dz+D/XXwD5+HtOHFg+HwAAAAASUVORK5CYII="
            alt="' . $slide->getAltText() . '" style="max-width: 100px;">';
        }
    }
}
