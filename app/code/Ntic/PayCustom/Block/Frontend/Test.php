<?php
namespace Ntic\PayCustom\Block\Frontend;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;

class Test extends \Magento\Framework\View\Element\Template
{
    protected $_cart;
    protected $_checkoutSession;
    protected $_objectManager;
    protected $_product;
    protected $_currentQuote;
    protected $_currentTotal = 0;
    protected $_paymentMethod = ['CB','CHECK','IBAN'];
    protected $_typeOrder;
    protected $_catProduct;
    protected $_nb_package;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\Product $product,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        array $data = []
    ){
        $this->_cart            = $cart;
        $this->_product         = $product;
        $this->_checkoutSession = $checkoutSession;
        $this->_objectManager   = $objectmanager;
        $this->_currentQuote    = $this->getCurrentQuote();
        parent::__construct($context, $data);
    }

    private function getQuote(){
        return $this->_cart->getQuote();
    }
    public function getTypeOrder(){
        return $this->_typeOrder;
    }
    public function getNumberPackage(){
        return $this->_nb_package;
    }
    private function setTyperOrder($typeOrder){
        $this->_typeOrder = $typeOrder;
        return true;
    }
    public function getCategoryProduct(){
        return strtolower($this->_catProduct);
    }
    private function setCategoryProduct($catProduct){
        $this->_catProduct = $catProduct;
        return true;
    }
    public function getListPaymentMethod(){
        return $this->_paymentMethod;
    }
    public function getFraction(){
        return $this->checkFraction($this->getGrandTotal());
    }
    public function getItemsQty(){
        return $this->getQuote()->getItemsQty();
    }
    public function getSubtotal(){
        return $this->getQuote()->getSubtotal();
    }
    public function getCurrentTotal(){
        return $this->_currentTotal;
    }
    public function getGrandTotal(){
        return $this->getQuote()->getGrandTotal();
        //return $this->_currentTotal + $this->getShippingPrice();
    }
    public function getShippingPrice(){
        return $this->getQuote()->getShippingAddress()->getShippingAmount();
        //return $this->getGrandTotal() - $this->getSubtotal();
    }

    public function getConfigPaymentSimple($to_type){
        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('ntic_config_payment_fraction');
        $sql = "SELECT * FROM ".$tableName." WHERE to_type = '".$to_type."'";
        $result = $connection->fetchAll($sql);
        return $result;
    }
    public function getCurrentQuote2(){
        return $this->_currentQuote;
    }
    public function getCurrentQuote(){
        $prepare_items = array();
        $result_prepare = array();
        // on recup les items du cart
        $items = $this->getQuote()->getAllItems();
        $cat = 'non';
        $catIds ='non';
        // prepare tous les items
        foreach($items as $item){
            $product = $this->_product->load($item->getProductId());
            $catIds = $product->getCategoryIds();

            foreach ($catIds as $catId){
                $cat = $this->_objectManager->create('Magento\Catalog\Model\Category')->load($catId);
            }

            // Détermine si dans la commande il y a un abo
            if($product->getCustomAttribute('subscription') != NULL && $product->getCustomAttribute('subscription')->getValue() == 1) {
                $order_type = 'subscription';
                // Nombre de colis sur les abo
                if($product->getCustomAttribute('packages_number') != NULL && $product->getCustomAttribute('packages_number')->getValue() > 1) {
                    $this->_nb_package = $product->getCustomAttribute('packages_number')->getValue();
                } else {
                    $this->_nb_package  = 1;
                }
            }else{
                $order_type = 'order';
            }

            $item_id = $item->getId();
            if( $item->getData('parent_item_id')){
                $idParent = $item->getData('parent_item_id');
                $ChildDetail = array();
                $ChildDetail['item_id']      = $item_id;
                $ChildDetail['product_id']   = $item->getProductId();
                $ChildDetail['item_name']    = $item->getName();
                $ChildDetail['item_sku']     = $item->getSku();
                $ChildDetail['item_px']      = $item->getPrice();
                $ChildDetail['item_qty']     = $item->getQty()*1;
                $ChildDetail['item_row']     = $item->getRowTotal();
                $ChildDetail['type_add']     = $order_type;
                $prepare_items[$order_type][$idParent]['component'][] = $ChildDetail;
                $prepare_items['opt'][$order_type]['total_global']    = (!isset($prepare_items['opt'][$order_type]['total_global']))?$item->getRowTotal():$prepare_items['opt'][$order_type]['total_global'] + $item->getRowTotal();
            }else{
                $prepare_items[$order_type][$item_id]['item_id']         = $item_id;
                $prepare_items[$order_type][$item_id]['product_id']      = $item->getProductId();
                $prepare_items[$order_type][$item_id]['item_name']       = $item->getName();
                $prepare_items[$order_type][$item_id]['item_sku']        = $item->getSku();
                $prepare_items[$order_type][$item_id]['item_px']         = $item->getPrice();
                $prepare_items[$order_type][$item_id]['item_qty']        = $item->getQty()*1;
                $prepare_items[$order_type][$item_id]['item_row']        = $item->getRowTotal();
                $prepare_items[$order_type][$item_id]['type_add']        = $order_type;
                $prepare_items['opt'][$order_type]['total_global']    = (!isset($prepare_items['opt'][$order_type]['total_global']))?$item->getRowTotal():$prepare_items['opt'][$order_type]['total_global'] + $item->getRowTotal();
            }
        }

        // regle si il y a un abo, on le traite en premier et on garde le prix
        if(isset($prepare_items['subscription']) && !empty($prepare_items['subscription'])) {
            $result_prepare[] = array_shift($prepare_items['subscription']);
            $this->_currentTotal = $result_prepare[0]['item_row'];
            $this->setTyperOrder('subscription');
            $this->setCategoryProduct($cat->getName());
        }elseif(isset($prepare_items['order']) && !empty($prepare_items['order'])){
            $this->_currentTotal = $prepare_items['opt']['order']['total_global'];
            $result_prepare = $prepare_items['order'];
            $this->setTyperOrder('order');
        }
        return $result_prepare;
    }

    public function checkFraction()
    {
        $total_global = $this->getGrandTotal();
        //$config_simple = $this->getConfigPaymentSimple($this->_typeOrder);
        $config_simple = $this->getConfigPaymentSimple("order");
        $count_amount = 0;
        $final_fraction = "2";
        foreach ($config_simple as $rule){
            switch ($rule['operateur']){
                case '<':
                    if($total_global < $rule['amount'] && $count_amount < $rule['amount']){
                        $final_fraction = $rule['fraction'];
                        $count_amount = $rule['amount'];
                    }
                    break;
                case '<=':
                    if($total_global <= $rule['amount'] && $count_amount < $rule['amount']){
                        $final_fraction = $rule['fraction'];
                        $count_amount = $rule['amount'];
                    }
                    break;
                case '>':
                    if($total_global > $rule['amount'] && $count_amount < $rule['amount']){
                        $final_fraction = $rule['fraction'];
                        $count_amount = $rule['amount'];
                    }
                    break;
                case '>=':
                    if($total_global >= $rule['amount'] && $count_amount < $rule['amount']){
                        $final_fraction = $rule['fraction'];
                        $count_amount = $rule['amount'];
                    }
                    break;
                case '==':
                    if($total_global == $rule['amount'] && $count_amount < $rule['amount']){
                        $final_fraction = $rule['fraction'];
                        $count_amount = $rule['amount'];
                    }
                    break;
                case '!=':
                    if($total_global != $rule['amount'] && $count_amount < $rule['amount']){
                        $final_fraction = $rule['fraction'];
                        $count_amount = $rule['amount'];
                    }
                    break;
                default:
                    $final_fraction = false;
                    break;
            }
        }
        return $final_fraction;
    }
}

?>