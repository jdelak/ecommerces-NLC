<?php
namespace Ntic\Sponsor\Block;
use Magento\Customer\Model\Session;
use Ntic\Common\Block\ContactMaster;
class Sponsor extends \Magento\Framework\View\Element\Template
{
    private $context;
    public function __construct(
        Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->_customerSession = $customerSession;
        $this->_categoryFactory = $categoryFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->listProductBlock = $listProductBlock;
        $this->context = $context;

    }
    public function getCustomerId(){
        return $this->_customerSession->getCustomer()->getId();
    }

    public function getProductGodfather(){

        $categoryId = 58;
        if($_SESSION['gift_godfather'] == 2){
            $categoryId = 59;
        }

        $category = $this->_categoryFactory->create()->load($categoryId);
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoryFilter($category);
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        return $collection;
    }

    public function getAddToCartPostParams($product)
    {
        return $this->listProductBlock->getAddToCartPostParams($product);
    }

    public function getGodfather(){
        $contactMaster = new ContactMaster($this->context);

        $result = $contactMaster->listGodfatherByCustomer(4231);
        return $result;

    }

}