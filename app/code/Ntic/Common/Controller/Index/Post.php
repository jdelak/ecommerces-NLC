<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ntic\Common\Controller\Index;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;




class Post extends \Magento\Framework\App\Action\Action
{

    protected $uploaderFactory;
    protected $adapterFactory;
    protected $filesystem;
    protected $storeManager;
    protected $_transportBuilder;

    public function __construct(

        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list
    ) {

        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->directory_list = $directory_list;
        parent::__construct($context);
    }


    public function execute()
    {

        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        $post = (array) $this->getRequest()->getPost();
        if (!$post) {
            $this->_redirect('*/*/');
            return;
        }

        $captcha    = $_POST['g-recaptcha-response'];
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdJZEsUAAAAAAdaTjucstOtgIsWp4vfcyZ_5-_K&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        $captcha_success=json_decode($verify);

        if (!empty($post && $captcha_success->success==true)) {
            $subject    = $post['subject'];
            $name       = $post['name'];
            $email      = $post['email'];
            $telephone  = $post['telephone'];

            if(isset($post['code_client'])){
                $codeclient = $post['code_client'];
            }else{
                $codeclient = '';
            }
            $comment    = $post['comment'];
            if(isset($_FILES['upload_document'])){
                $tmpFilePath = $_FILES['upload_document']['tmp_name'];
            }



            // Send Mail functionality starts from here
                        $from = $email;
                        $nameFrom = $name;
                        if($websiteId == 3){ //Time Nutrition
                            $to = "serviceclients@time-nutrition.com";
                        }else if($websiteId == 15){
                            $to = "commercial@lexel-paris.com";
                        }else{
                            $to = "service.clients@lexel-paris.com";
                        }

                        $nameTo = 'Service Client';
                        $body = "
                        
                        <p>
                            <h2>Sujet : ".$subject."</h2>
                        </p>
                        <p>
                            Nom : ".$name."
                        </p>

                        <p>E-Mail : ".$email."</p>
                        <p>
                            Téléphone :".$telephone."

                        </p>
                        <p>
                            Code Client : ".$codeclient."
                        </p>
                        <p>
                            Message : ".$comment."
                        </p>";


            $email = new \Zend_Mail();
            if($websiteId == 3){// Time Nutrition
                $email->setSubject('Formulaire Contact Time Nutrition');
            }else if($websiteId == 15){
                $email->setSubject('Formulaire Contact Lexel Professionnel');
            }else{
                $email->setSubject('Formulaire Contact Lexel Paris');
            }

            $email->setBodyHtml($body);     // use it to send html data
            //$email->setBodyText($body);   // use it to send simple text data
            $email->setFrom($from, $nameFrom);
            $email->addTo($to, $nameTo);
            //$email->addBcc('webmaster@lexel-paris.com');
            if(isset($_FILES['upload_document']['tmp_name'])&& $_FILES['upload_document']['error'][0] == 0  && $_FILES['upload_document']['tmp_name'] != '' ){
                $email->createAttachment(
                    file_get_contents($tmpFilePath),
                    $_FILES['upload_document']['type'],
                    \Zend_Mime::DISPOSITION_ATTACHMENT,
                    \Zend_Mime::ENCODING_BASE64,
                    $_FILES['upload_document']['name']
                );
            }


            $email->send();


            
            $this->inlineTranslation->resume();
            $this->messageManager->addSuccess(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
            $this->_redirect('contact/index');
            return;

            
        }else{
            $this->inlineTranslation->resume();
            $this->messageManager->addError(
                __('Veuillez renseigner tous les champs obligatoires et valider que vous n\'êtes pas un robot' )
            );
            echo "<p>You are a bot! Go away!</p>";
            $this->_redirect('*/*/');
        }
    }


}
