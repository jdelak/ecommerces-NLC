<?php

namespace Ntic\LexelRecruit\Controller\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Index extends \Magento\Framework\App\Action\Action
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

    )
    {

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
        $post = (array)$this->getRequest()->getPost();

        if (!empty($post)) {

            $civilite = $post['civilite'];
            $firstname = $post['firstname'];
            $lastname = $post['name'];
            $phone = $post['phone'];
            $mail = $post['mail3'];
            $address = $post['address'];
            $address2 = $post['address2'];
            $cp = $post['cp'];
            $city = $post['city'];
            $subject = $post['subject'];
            $message = $post['message'];
            if ($_FILES) {
                $tmpFilePath = $_FILES['cv']['tmp_name'];
            }


            $body = "
                <h1>Une nouvelle candidature via le Site Lexel Paris a été reçu :</h1>
                <br>
                <p>
                    Sujet : " . $subject . "
                </p>
                <p>
                    " . $civilite . " " . $firstname . " " . $lastname . "
                </p>
                <p>Téléphone : " . $phone . "</p>
                <p>E-Mail : " . $mail . "</p>
                <p>
                    Adresse :<br>"
                . $address . "<br>"
                . $address2 . "<br>"
                . $cp . "<br>"
                . $city . "
                </p>
                <p>"
                . $message . "
                </p>
            ";

            $email = new \Zend_Mail();
            $email->setSubject('Formulaire Recrutement Lexel (site)');
            $email->setBodyHtml($body);     // use it to send html data
            //$email->setBodyText($body);   // use it to send simple text data
            $email->setFrom($mail, $firstname . ' ' . $lastname);
            $email->addTo('rh@lexel-paris.com');
            $email->addBcc('webmaster@lexel-paris.com');
            if ($_FILES['cv']['tmp_name']) {
                $email->createAttachment(
                    file_get_contents($tmpFilePath),
                    $_FILES['cv']['type'],
                    \Zend_Mime::DISPOSITION_ATTACHMENT,
                    \Zend_Mime::ENCODING_BASE64,
                    $_FILES['cv']['name']
                );
            }

            $email->send();


            // Display the succes form validation message
            $this->messageManager->addSuccessMessage('Candidature Envoyée !');

            // Redirect to your form page (or anywhere you want...)
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl('/recruit');


            return $resultRedirect;
        }
        // Render the page
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
