<?php
namespace Ntic\InformationCustomer\Block;
use Ntic\Common\Block\ContactMaster;
class Attribute extends \Magento\Framework\View\Element\Template
{
    private $context;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    )
    {
        parent::__construct($context);
        $this->context = $context;
    }

    public function getAttribute($name){
        $contactMaster = new ContactMaster($this->context);
        return $contactMaster->getAttributeByName($name);


    }
}
?>