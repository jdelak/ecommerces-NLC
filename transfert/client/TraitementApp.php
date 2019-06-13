<?php

class TraitementApp extends \Magento\Framework\App\Http
implements \Magento\Framework\AppInterface
{
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_storeManager;
    protected $_objectManager;
    protected $customerFactory;
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

        echo "run begin \n<br>";

        $websiteId = $_storeManager->getWebsite()->getWebsiteId();
        $store = $_storeManager->getStore();
        $compteur = file_get_contents("compteur.txt");
        // Import Customer csv
        /*
        * $data[0] = email
        * $data[1] = group_id
        * $data[2] = prefix
        * $data[3] = firstname
        * $data[4] = middlename
        * $data[5] = lastname
        * $data[6] = codeclient
        * $data[7] = codeconseillere
        * $data[8] = dob
        * $data[9] = zipcode
        * $data[10] = city
        * $data[11] = country
        * $data[12] = street
        * $data[13] = region
        * $data[14] = telephone
        */
        //On délimite le nombre maximum de client à importer à chaque run
        $max = intval($compteur) + 1;
        //
        try{
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            $websiteId = $_storeManager->getWebsite()->getWebsiteId();
            $store = $_storeManager->getStore();
            $storeId = $store->getStoreId();
            // CUSTOMER  GESCO
            for($i=$compteur;$i <= $max;$i++){
                $item = $this->getCustomerGesco($i);
                $region = intval(substr($item[8],0,2)) + 181;
                $mail   = ($item[15]=='')?$item[0].'@lexel.fr':$item[15];
                $mobile = '';
                if($item[12]!=''){
                    $tel    = $item[12];
                    $mobile = ($item[13] == '')?$item[14]:$item[13];
                }elseif($item[12]=='' && $item[13]!=''){
                    $tel = $item[13];
                }elseif($item[12]=='' && $item[13]=='' && $item[14]!=''){
                    $tel = $item[14];
                }else{
                    $tel = '0';
                }
                $data=[
                    $mail,
                    trim($item[17]),
                    trim($item[2]),
                    trim($item[4]),
                    null,
                    trim($item[3]),
                    trim($item[0]),
                    null,
                    trim($item[28]),
                    trim($item[8]),
                    trim($item[9]),
                    trim($item[11]),
                    [$item[5],$item[6],$item[7]],
                    $region,
                    trim($tel),
                    trim($mobile)
                ];
                //-------------------------------> Création du client
                $customer = $_objectManager->create('\Magento\Customer\Model\Customer');
                $customer->setWebsiteId($websiteId);
                $customerExist = $customer->loadByEmail($mail);

                if($data[3] == '' || $data[5]==''){
                    echo 'Customer ' . $data[0] . ' no firstname or name <br>';
                }else if(!filter_var($data[0], FILTER_VALIDATE_EMAIL)){
                    echo 'Customer ' . $data[0] . ' f****** email <br>';
                }else if ($customerExist->getId()) {
                    echo 'Customer ' . $data[0] . ' already exists <br>';
                } else {
                    $customer->setEmail($data[0]);
                    $customer->setPrefix($data[2]);
                    $customer->setFirstname($data[3]);
                    $customer->setLastname($data[5]);
                    $customer->setData('code_client_gesco', $data[6]);
                    $customer->setData('mobile', $data[15]);
                    if ($data[2] == 'MR') {
                        $customer->setGender(1);
                    } else {
                        $customer->setGender(2);
                    }
                    //$customer->setSellerNumber($data[7]);
                    var_dump($customer->getData());
                    $addresss = $_objectManager->get('\Magento\Customer\Model\AddressFactory');
                    $state = $_objectManager->create('\Magento\Framework\App\State');
                    $state->setAreaCode('frontend');
                    $address = $addresss->create();
                    $address->setCustomerId(4)
                        ->setFirstname('Mav')
                        ->setLastname('rick')
                        ->setCountryId('HR')
                        //->setRegionId('1') //state/province, only needed if the country is USA
                        ->setPostcode('31000')
                        ->setCity('Osijek')
                        ->setTelephone('0038511223344')
                        ->setFax('0038511223355')
                        ->setCompany('GMI')
                        ->setStreet('NO:12 Lake View')
                        ->setIsDefaultBilling('1')
                        ->setIsDefaultShipping('1')
                        ->setSaveInAddressBook('1');

                        $address->save();
                    exit();
                    exit();
                    $customer->save();
                    echo 'create customer successfully ' . $data[0] . '<br>' ;
                    //--------------------------------->ajout de l'addresse du client
                    $address = $_objectManager->create('\Magento\Customer\Model\Address');
                    $address->setCustomerId($customer->getId());

                    $address->setFirstname($data[3]);
                    $address->setLastname($data[5]);
                    $address->setCountryId('FR');
                    $address->setRegion($data[13]);

                    $address->setPostcode($data[9]);
                    $address->setCity($data[10]);
                    $address->setTelephone($data[14]);

                    $address->setCompany();
                    $address->setStreet($data[12]);

                    $address->setIsDefaultBilling('1');
                    $address->setIsDefaultShipping('1');
                    $address->setSaveInAddressBook('1');
                    var_dump($address->getData());
                    var_dump($address->save());die();


                    try {
                        $address->save();
                    }catch (Exception $e) {
                        Zend_Debug::dump($e->getMessage());
                    }
                    echo 'address created for customer ' . $data[0]  . '<br>';
                }
                $text = strval($i);

                file_put_contents("compteur.txt", ''.$text);


            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $this->_response;

    }
    public function getCustomerGesco($cpt){
        $bdd = new PDO('mysql:host=localhost;dbname=image_gesco_RO','root','Kay8lWiBMQwu');
        $sql = $bdd->query("SELECT * FROM gcliact WHERE gclddcd >= '2010-01-01' AND gclnom <> 'CNIL' LIMIT ".$cpt." , 1");
        //SELECT * FROM gcliact WHERE gclddcd >= '2010-01-01' AND gclnom <> 'CNIL' AND gclmail = '' AND gcltele = '' AND gcltel2 = '' AND gclfax = '' ORDER BY `gcliact`.`gcladr1` ASC
        $results = $sql->fetch();
        return $results;
    }
    public function mappingAddress($data){
        $return = '';
        if ($data == 'Ain') {
            $return = 182;
        }
        if ($data == 'Aisne') {
            $return = 183;
        }
        if ($data == 'Allier') {
            $return = 184;
        }
        if ($data == 'Alpes-de-Haute-Provence') {
            $return = 185;
        }
        if ($data == 'Hautes-Alpes') {
            $return = 186;
        }
        if ($data == 'Alpes-Maritimes' || $data == 'Alpes-Maritime') {
            $return = 187;
        }
        if ($data == 'Ardèche') {
            $return = 188;
        }
        if ($data == 'Ardennes') {
            $return = 189;
        }
        if ($data == 'Ariège') {
            $return = 190;
        }
        if ($data == 'Aube') {
            $return = 191;
        }
        if ($data == 'Aude') {
            $return = 192;
        }
        if ($data == 'Aveyron') {
            $return = 193;
        }
        if ($data == 'Bouches-du-Rhône') {
            $return = 194;
        }
        if ($data == 'Calvados') {
            $return = 195;
        }
        if ($data == 'Cantal') {
            $return = 196;
        }
        if ($data == 'Charente') {
            $return = 197;
        }
        if ($data == 'Charente-Maritime') {
            $return = 198;
        }
        if ($data == 'Cher') {
            $return = 199;
        }
        if ($data == 'Corrèze') {
            $return = 200;
        }
        if ($data == 'Corse-du-Sud') {
            $return = 201;
        }
        if ($data == 'Haute-Corse') {
            $return = 202;
        }
        if ($data == 'Côte-d\'Or' || $data == 'Côte-d or') {
            $return = 203;
        }
        if ($data == 'Côtes-d\'Armor') {
            $return = 204;
        }
        if ($data == 'Creuse') {
            $return = 205;
        }
        if ($data == 'Dordogne') {
            $return = 206;
        }
        if ($data == 'Doubs') {
            $return = 207;
        }
        if ($data == 'Drôme') {
            $return = 208;
        }
        if ($data == 'Eure') {
            $return = 209;
        }
        if ($data == 'Eure-et-Loir') {
            $return = 210;
        }
        if ($data == 'Finistère') {
            $return = 211;
        }
        if ($data == 'Gard') {
            $return = 212;
        }
        if ($data == 'Haute-Garonne') {
            $return = 213;
        }
        if ($data == 'Gers') {
            $return = 214;
        }
        if ($data == 'Gironde') {
            $return = 215;
        }
        if ($data == 'Hérault') {
            $return = 216;
        }
        if ($data == 'Ille-et-Vilaine' || $data == 'Ile-et-Vilaine') {
            $return = 217;
        }
        if ($data == 'Indre') {
            $return = 218;
        }
        if ($data == 'Indre-et-Loire') {
            $return = 219;
        }
        if ($data == 'Isère') {
            $return = 220;
        }
        if ($data == 'Jura') {
            $return = 221;
        }
        if ($data == 'Landes') {
            $return = 222;
        }
        if ($data == 'Loir-et-Cher' || $data == 'Loir-et-cher') {
            $return = 223;
        }
        if ($data == 'Loire' || $data == 'loire') {
            $return = 224;
        }
        if ($data == 'Haute-Loire') {
            $return = 225;
        }
        if ($data == 'Loire-Atlantique') {
            $return = 226;
        }
        if ($data == 'Loiret' || $data == 'loiret') {
            $return = 227;
        }
        if ($data == 'Lot') {
            $return = 228;
        }
        if ($data == 'Lot-et-Garonne' || $data == 'lot-et-Garonne') {
            $return = 229;
        }
        if ($data == 'Lozère') {
            $return = 230;
        }
        if ($data == 'Maine-et-Loire') {
            $return = 231;
        }
        if ($data == 'Manche') {
            $return = 232;
        }
        if ($data == 'Marne') {
            $return = 233;
        }
        if ($data == 'Haute-Marne') {
            $return = 234;
        }
        if ($data == 'Mayenne') {
            $return = 235;
        }
        if ($data == 'Meurthe-et-Moselle') {
            $return = 236;
        }
        if ($data == 'Meuse') {
            $return = 237;
        }
        if ($data == 'Morbihan') {
            $return = 238;
        }
        if ($data == 'Moselle') {
            $return = 239;
        }
        if ($data == 'Nièvre') {
            $return = 240;
        }
        if ($data == 'Nord') {
            $return = 241;
        }
        if ($data == 'Oise') {
            $return = 242;
        }
        if ($data == 'Orne') {
            $return = 243;
        }
        if ($data == 'Pas-de-Calais') {
            $return = 244;
        }
        if ($data == 'Puy-de-Dôme') {
            $return = 245;
        }
        if ($data == 'Pyrénées-Atlantiques') {
            $return = 246;
        }
        if ($data == 'Hautes-Pyrénées') {
            $return = 247;
        }
        if ($data == 'Pyrénées-Orientales') {
            $return = 248;
        }
        if ($data == 'Bas-Rhin') {
            $return = 249;
        }
        if ($data == 'Haut-Rhin') {
            $return = 250;
        }
        if ($data == 'Rhône') {
            $return = 251;
        }
        if ($data == 'Haute-Saône') {
            $return = 252;
        }
        if ($data == 'Saône-et-Loire') {
            $return = 253;
        }
        if ($data == 'Sarthe') {
            $return = 254;
        }
        if ($data == 'Savoie') {
            $return = 255;
        }
        if ($data == 'Haute-Savoie') {
            $return = 256;
        }
        if ($data == 'Paris') {
            $return = 257;
        }
        if ($data == 'Seine-Maritime') {
            $return = 258;
        }
        if ($data == 'Seine-et-Marne') {
            $return = 259;
        }
        if ($data == 'Yvelines') {
            $return = 260;
        }
        if ($data == 'Deux-Sèvres') {
            $return = 261;
        }
        if ($data == 'Somme') {
            $return = 262;
        }
        if ($data == 'Tarn') {
            $return = 263;
        }
        if ($data == 'Tarn-et-Garonne') {
            $return = 264;
        }
        if ($data == 'Var') {
            $return = 265;
        }
        if ($data == 'Vaucluse') {
            $return = 266;
        }
        if ($data == 'Vendée') {
            $return = 267;
        }
        if ($data == 'Vienne') {
            $return = 268;
        }
        if ($data == 'Haute-Vienne') {
            $return = 269;
        }
        if ($data == 'Vosges') {
            $return = 270;
        }
        if ($data == 'Yonne') {
            $return = 271;
        }
        if ($data == 'Territoire-de-Belfort') {
            $return = 272;
        }
        if ($data == 'Essonne') {
            $return = 273;
        }
        if ($data == 'Hauts-de-Seine') {
            $return = 274;
        }
        if ($data == 'Seine-Saint-Denis') {
            $return = 275;
        }
        if ($data == 'Val-de-Marne') {
            $return = 276;
        }
        if ($data == 'Val-d\'Oise' || $data == 'Val-d Oise') {
            $return = 277;
        }
        return $return;
    }
}