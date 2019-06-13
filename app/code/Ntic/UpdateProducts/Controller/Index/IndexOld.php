<?php

namespace Ntic\UpdateProducts\Controller\Index;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Magento\Framework\App\ObjectManager;
use Ntic\UpdateProducts\Helper\Navision\CreateOrderNAV;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $connectionFactory;
    protected $_storeManager;
    protected $productRepository;
    protected $stockRegistry;
    protected $_indexerCollectionFactory;
    protected $categoryFactory;
    protected $objectManager;
    protected $_helper;
    protected $_resource;


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Ntic\UpdateProducts\Helper\Navision\GetProductNAV $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Ntic\UpdateProducts\Helper\Navision\GetProductNAV $helper,
        \Magento\Framework\App\ResourceConnection\ConnectionFactory $connectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Indexer\Model\Indexer\CollectionFactory $indexerCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resource

        // \Magento\Catalog\Model\Category $category

    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->connectionFactory = $connectionFactory;
        $this->_storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->stockRegistry = $stockRegistry;
        $this->_indexerCollectionFactory = $indexerCollectionFactory;
        $this->objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $this->_helper = $helper;
        $this->_resource = $resource;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */

    public function execute(){
        //$this->executeNav();
    }

    private function executeNav()
    {
        // RECUPERATION produit GESCO
        $xml = $this->_helper->createOrderServiceNAV();
        var_dump($xml);
        die();
        $results = $xml->ReadMultiple_Result->ItemWS;


        $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);


        foreach ($results as $row) {
            $product = $this->objectManager->create('Magento\Catalog\Model\Product');
            try{
                // -----------------------------------------------------------> On test si le produit existe déjà dans Magento
                if($product->getIdbySku($row->No)){

                    $productID = $product->getIdbySku($row->No);
                    $productexistant = $product->load((int)$productID);
                    $status = 1;
                    if($row->Blocked){
                        $status = 2;
                    }

                    $idProduct =  $productexistant->getId();

                    if($productexistant->getData('status') != $status){

                        echo $idProduct.' doit être mis à jour '.$status.'<br>';

                        $connection->exec("UPDATE `catalog_product_entity_int` 
                                           SET `value`=$status 
                                           WHERE attribute_id = ( SELECT attribute_id 
                                                                  FROM `eav_attribute`
                                                                  WHERE `attribute_code` LIKE 'status' ) 
                                          AND catalog_product_entity_int.entity_id = '$idProduct'");

                        echo $productexistant->getName(). ' mis à jour<br>';
                    }else{
                        echo $idProduct.' RAS <br>';
                    }
                }else{
                    // ------------------------------------------------------> Sinon on le crée
                    echo $row->No.' doit être crée<br>';
                    $inventoryQty = 0;
                    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
                    $product->setAttributeSetId(4);
                    $product->setWebsiteIds([$this->_storeManager->getWebsite()->getId()]);
                    if($row->Blocked){
                        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
                    }else{
                        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
                    }
                    $product->setStockData(array(
                        'use_config_manage_stock' => 0, //'Use config settings' checkbox
                        'manage_stock' => 0, //manage stock
                        'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                        'is_in_stock' => 1, //Stock Availability
                        'qty' => $inventoryQty //qty
                    ));
                    $product->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);
                    $product->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
                    $product->setName(utf8_encode($row->Description));
                    $product->setSku($row->No);
                    $product->setUrlKey($row->Description.'-'.$row->No);
                    $product->setIsMassupdate(true);
                    $product->setExcludeUrlRewrite(true);
                    $product->save();
                    echo $product->getName(). ' ajouté<br>';
                }

            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }

    private function executeGesco()
    {
        // RECUPERATION produit GESCO
        $results = $this->getGproduiGesco();
        $results2 = $this->getLxentaboGesco();


        foreach ($results as $row) {
            $product = $this->objectManager->create('Magento\Catalog\Model\Product');
            try{
                // -----------------------------------------------------------> On test si le produit existe déjà dans Magento
                if($product->getIdbySku($row["gprcode"])){
                    $productID = $product->getIdbySku($row["gprcode"]);
                    $productexistant = $product->load((int)$productID);
                    if($productexistant->getData('price') != $row["gpcprxv"]){
                        echo $row['gprcode'].' doit être mis à jour<br>';
                        $productexistant->setData('price', $row["gpcprxv"]);
                        $productexistant->save();
                        echo $productexistant->getName(). ' mis à jour<br>';
                    }else{
                        echo $row["gprcode"].' RAS <br>';
                    }
                    /*
                    $inventoryQty = ($row["gprstto"] > 0) ? $row["gprstto"] : 0;
                    $isInStock = ($inventoryQty > 0) ? 1 : 0;
                    $stockItem = $this->stockRegistry->getStockItemBySku($productexistant["sku"]);
                    $stockItem->setQty($inventoryQty);
                    $stockItem->setIsInStock($isInStock);
                    $this->stockRegistry->updateStockItemBySku($row["gprcode"], $stockItem);
                    $stockItem->save();
                    */
                }else{
                    // ------------------------------------------------------> Sinon on le crée
                    echo $row['gprcode'].' doit être crée<br>';
                    $inventoryQty = ($row["gprstto"] > 0) ? $row["gprstto"] : 0;
                    $isInStock = ($inventoryQty > 0) ? 1 : 0;
                    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
                    $product->setAttributeSetId(4);
                    $product->setWebsiteIds([$this->_storeManager->getWebsite()->getId()]);
                    if($row['gpracti'] == 0){
                        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
                    }else{
                        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
                    }
                    $product->setStockData(array(
                        'use_config_manage_stock' => 0, //'Use config settings' checkbox
                        'manage_stock' => 0, //manage stock
                        'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                        'is_in_stock' => 1, //Stock Availability
                        'qty' => $inventoryQty //qty
                    ));
                    $product->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);
                    $product->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
                    $product->setPrice($row["gpcprxv"]);
                    $product->setWeight($row['gprpdbr']);
                    $product->setName(utf8_encode($row["gprdesi"]));
                    $product->setSku($row["gprcode"]);
                    $product->setUrlKey($row["gprdesi"].'-'.$row["gprcode"]);
                    $product->setIsMassupdate(true);
                    $product->setExcludeUrlRewrite(true);
                    $product->save();
                    echo $product->getName(). ' ajouté<br>';
                }

                /*
                $indexers = $this->_indexerCollectionFactory->create()->getItems();
                foreach ($indexers as $indexer) {
                    $indexer->reindexAll();
                }
                */
            } catch (Exception $e) {
                $e->getMessage();
            }
        }

        $this->createCategoryProduct(); //création de la catégorie abonnement si elle n'existe pas.
        foreach ($results2 as $row) {


            $product = $this->objectManager->create('Magento\Catalog\Model\Product');
            $catId = $this->getCategoryProduct('Abonnement');
            try{


                // -----------------------------------------------------------> On test si le produit existe déjà dans Magento
                if($product->getIdbySku($row["earefa"])){
                    $productID = $product->getIdbySku($row["earefa"]);

                    $productexistant = $product->load((int)$productID);
                    if($productexistant->getData('price') != $row["eapttc"]){
                        echo $row['earefa'].' doit être mis à jour<br>';
                        $productexistant->setData('price', $row["eapttc"]);
                        $productexistant->save();
                        echo $productexistant->getName(). ' mis à jour<br>';
                    }else{
                        echo $row["earefa"].' RAS <br>';
                    }
                }else{
                    // ------------------------------------------------------> Sinon on le crée
                    echo $row['earefa'].' doit être crée<br>';
                    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
                    $product->setAttributeSetId(4);
                    $product->setWebsiteIds([$this->_storeManager->getWebsite()->getId()]);
                    if($row['eaacti'] == 'O'){
                        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
                    }else{
                        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
                    }
                    $product->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID);
                    $product->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
                    $product->setPrice($row["eapttc"]);
                    $product->setName(utf8_encode($row["eanoma"]));
                    $product->setSku($row["earefa"]);
                    $product->setUrlKey($row["eanoma"].'-'.$row["earefa"]);
                    $product->setIsMassupdate(true);
                    $product->setExcludeUrlRewrite(true);
                    $product->setCategoryIds($catId);
                    $product->save();
                    echo $product->getName(). ' ajouté<br>';
                }

            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }

    private function getGproduiGesco(){
        $bdd = $this->connectionFactory->create(array(
            'host' => 'localhost',
            'dbname' => 'image_gesco_RO',
            'username' => 'root',
            'password' => 'Kay8lWiBMQwu',
            'active' => '1',
        ));

        $sql = 'SELECT gprcode, gprdesi, gpracti, gprpdbr, gprstto, gpcprxv
                    FROM gprodui LEFT JOIN gprocam on gprodui.gprcode = gprocam.gpcprod
                    WHERE gpccamp = 1';

        $results = $bdd->fetchAll($sql);
        return $results;
    }

    private function getLxentaboGesco(){
        $bdd = $this->connectionFactory->create(array(
            'host' => 'localhost',
            'dbname' => 'image_gesco_RO',
            'username' => 'root',
            'password' => 'Kay8lWiBMQwu',
            'active' => '1',
        ));

        $sql = 'SELECT earefa, eanoma, eaacti, eapttc
                    FROM lxentabo';

        $results = $bdd->fetchAll($sql);
        return $results;
    }

    private function createCategoryProduct(){
        $categoryFactory=$this->objectManager->get('\Magento\Catalog\Model\CategoryFactory');
        $category = 'Abonnement';
        if(!$this->getCategoryProduct($category)){
            echo "ok";
            /// Add a new sub category under root category
            $categoryTmp = $categoryFactory->create();
            $categoryTmp->setName($category);
            $categoryTmp->setIsActive(true);
            $categoryTmp->setParentId(1);
            $categoryTmp->setPath(1);
            $categoryTmp->save();

        }
        return true;
    }

    private function getCategoryProduct($name){
        $category = $this->objectManager->create('Magento\Catalog\Model\CategoryFactory');
        $collection = $category->create()->getCollection()->addFieldToFilter('name',$name);
        return $collection->getFirstItem()->getId();
    }
}

?>
