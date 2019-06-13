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

namespace SolideWebservices\Flexslider\Controller\Adminhtml\Group;

use Magento\Backend\App\Action;
use SolideWebservices\Flexslider\Model\GroupFactory;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\Registry;

class Products extends \Magento\Backend\App\Action
{
    /**
     * Variable.
     *
     * @var \SolideWebservices\Flexslider\Model\GroupFactory
     */
    protected $groupFactory;

    /**
     * Variable.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Variable.
     *
     * @var inherit
     */
    protected $resultRedirectFactory;

    /**
     * Variable.
     *
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Construct.
     *
     * @param Action\Context $context             Context.
     * @param GroupFactory   $groupFactory        GroupFactory.
     * @param LayoutFactory  $resultLayoutFactory ResultLayoutFactory.
     * @param Registry       $registry            Registry.
     */
    public function __construct(
        Action\Context $context,
        GroupFactory $groupFactory,
        LayoutFactory $resultLayoutFactory,
        Registry $registry
    ) {
        $this->groupFactory = $groupFactory;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Check the permission to run it.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed('SolideWebservices_Flexslider::group');
    }

    /**
     * Index action.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $groupId = (int) $this->getRequest()->getParam('group_id');
        $group = $this->groupFactory->create();
        if ($groupId) {
            $group->load($groupId);
        }
        $this->_coreRegistry->register('flexslider_group', $group);

        $resultLayout = $this->resultLayoutFactory->create();

        $productsBlock = $resultLayout->getLayout()
            ->getBlock('flexslider_group_product_edit_tab_product');
        if ($productsBlock) {
            $productsBlock->setGroupProducts(
                $this->getRequest()->getPost('group_products', null)
            );
        }
        return $resultLayout;
    }
}
