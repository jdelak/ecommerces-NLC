<?php

class TraitementApp extends \Magento\Framework\App\Http
    implements \Magento\Framework\AppInterface
{
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_storeManager;
    protected $_objectManager;
    protected $response_perso;




    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function launch()
    {


        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_storeManager = $_objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        $rulesFactory = $_objectManager->create('\Magento\SalesRule\Model\RuleFactory');

        /*$resource = $_objectManager->create('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();*/

        $compteur = file_get_contents("compteur.txt");
        // Import Coupon Code csv
        /*
        * $data[0] = name
        * $data[1] = description
        * $data[2] = from_date
        * $data[3] = to_date
        * $data[4] = usage_per_customer
        * $data[5] = stop_rules_processing
        * $data[6] = simple action
        * $data[7] = discount_amount
        * $data[8] = discount_qty
        * $data[9] = discount_step
        * $data[10] = times_used
        * $data[11] = coupon_type
        * $data[12] = usage_limit
        * $data[13] = customer_group_ids
        * $data[14] = coupon_code
        * $data[15} = label
        * $data[16} = conditions_serialized
        * $data[17} = actions_serialized
         *
         * // remplacer salesrule/rule_condition_combine et salesrule/rule_condition_product_combine par \Magento\SalesRule\Model\Rule\Condition\Product\Combine
        */
        //On délimite le nombre maximum de client à importer à chaque run
        $max = intval($compteur) + 100;
        //
        try{
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            $websiteId = $_storeManager->getWebsite()->getWebsiteId();
            $store = $_storeManager->getStore();
            $storeId = $store->getStoreId();
            $lines = file("../../var/coupon_lexel.csv");
            for($i=$compteur;$i <= $max;$i++){
                echo $i . '<br>';
                $data=explode(';',$lines[$i]);

                if ($data[2] != 'NULL' || $data[3] != 'NULL') {
                    list($d, $m, $Y) = explode("/", substr($data[2], 0, 10));
                    list($d2, $m2, $Y2) = explode("/", substr($data[3], 0, 10));
                    $data[2] = $Y . '-' . $m . '-' . $d;
                    $data[3] = $Y2 . '-' . $m2 . '-' . $d2;

                }
                $data[16] = str_replace(",",";",$data[16]);
                $data[17] = str_replace(",",";",$data[17]);
                //var_dump($data);
                /*if($data[11] == 1){$couponType = "NO_COUPON";}
                if($data[11] == 2){$couponType = "SPECIFIC";}
                if($data[11] == 3){$couponType = "AUTO";}*/
                //-------------------------------> Création du client
                $ruleData =[
                    "name" => $data[0],
                    "description" => $data[1],
                    "from_date" => $data[2],
                    "to_date" => $data[3],
                    "uses_per_customer" => $data[4],
                    "is_active" => "1",
                    "stop_rules_processing" => $data[5],
                    "is_advanced" => "1",
                    "product_ids" => null,
                    "sort_order" => "0",
                    "simple_action" => $data[6],
                    "discount_amount" => $data[7],
                    "discount_qty" => $data[8],
                    "discount_step" => $data[9],
                    "apply_to_shipping" => "0",
                    "times_used" => $data[10],
                    "is_rss" => "0",
                    "coupon_type" =>$data[11], //$data[11]
                    "use_auto_generation" => "0",
                    "uses_per_coupon" => $data[12],
                    "simple_free_shipping" => "0",
                    "customer_group_ids" => [0,1,4,5,6,7,8,9,10,11], //[$data[13]]
                    //rajouter 9,10,11 pour prod
                    "website_ids" => [$websiteId], //
                    "coupon_code" => $data[14],
                    "store_labels" => [$data[15]],
                    "conditions_serialized" => '', //$data[16]
                    "actions_serialized" => '' //$data[17]
                ];



                $coupon = $_objectManager->create('\Magento\SalesRule\Model\Coupon');
                $ruleId = $coupon->loadByCode($data[14]);

                if ($ruleId->getId()) {
                    $id = $ruleId->getId();
                    echo 'Coupon Code ' . $data[0] . ' already exists <br>';
                    /*
                    $sql="UPDATE salesrule SET conditions_serialized ='', actions_serialized='' WHERE  rule_id='$id'";
                    $connection->query($sql);
                    echo 'action and conditions updated<br>';
                    */
                } else {

                    $ruleModel = $rulesFactory->create();
                    $ruleModel->setData($ruleData);
                    $ruleModel->save();

                    echo 'Coupon code ' . $data[14] . ' imported<br>' ;

                }

                $text = strval($i);
                file_put_contents("compteur.txt", ''.$text);
            }


        } catch (Exception $e) {
            var_dump($e->getMessage());
        }


        return $this->_response;

    }


}