<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.4
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Controller\Adminhtml\Group;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * Variable.
     *
     * @var Filter
     */
    protected $filter;

    /**
     * Variable.
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Construct.
     *
     * @param Context           $context           Context.
     * @param Filter            $filter            Filter.
     * @param CollectionFactory $collectionFactory CollectionFactory.
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception Ex.
     */
    public function execute()
    {
        $collection = $this->filter
            ->getCollection($this->collectionFactory->create());
        $records = 0;

        foreach ($collection as $group) {
            if($group->countSlideIds($group->getId()) == 0) {
                $group->delete();
                $records++;
            } else {
                $this->messageManager
                    ->addError(__('Group "%1" has slides connected, please disconnect all slides before deleting this group.',
                                    $group->getTitle()));
            }

        }

        $this->messageManager
            ->addSuccess(__('A total of %1 record(s) have been deleted.',
                        $records));

        $resultRedirect = $this->resultFactory
            ->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
