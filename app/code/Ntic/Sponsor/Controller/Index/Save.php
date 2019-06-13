<?php


namespace Ntic\Sponsor\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Ntic\Common\Block\ContactMaster;

class Save extends \Magento\Framework\App\Action\Action {
    protected $resultPageFactory;
    protected $jsonHelper;
    protected $contactMaster;
    protected $customerSession;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Element\Template\Context $contextView,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->resultFactory = $context->getResultFactory();
        $this->contactMaster = new ContactMaster($contextView);
        $this->customerSession = $customerSession;

        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->addSponsor($this->getRequest()->getPostValue());
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        return $result->setPath('sponsor/index');
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }

    /**
     * Add sponsor
     *
     * @param $post
     */
    public function addSponsor($post) {
        $_SESSION['message'] = array();
        if (isset($post)) {
            for ($i = 0; $i <= count($post) - 1; $i++) {
                $check_contact = true;
                // If key exist
                if (array_key_exists('contact_' . $i, $post)) {
                    // Init var for contact is exist
                    if (!empty($post['contact_' . $i]['email'])) {
                        $check_contact = $this->contactMaster->isContactExist('email', $post['contact_' . $i]['email']);
                    } else {
                        $check_contact = $this->contactMaster->isContactExist('tel1', $post['contact_' . $i]['phone']);
                    }

                    // Check if contact exist
                    if ($check_contact == false) {
                        // No error on formRules
                        if ($this->formRules($post['contact_' . $i]) === true) {
                            // CONTACT
                            $data_contact[] = array(
                                'lastname'     => isset($post['contact_' . $i]['lastname'])  ? $post['contact_' . $i]['lastname']   : '',
                                'firstname'    => isset($post['contact_' . $i]['firstname']) ? $post['contact_' . $i]['firstname']  : '',
                                'email'        => isset($post['contact_' . $i]['email'])     ? $post['contact_' . $i]['email']      : '',
                                'tel1'         => isset($post['contact_' . $i]['phone'])     ? (int)$post['contact_' . $i]['phone'] : '',
                                'created_at'   => date('Y-m-d H:i:s'),
                                'exploited_at' => date('Y-m-d H:i:s'),
                            );

                            $this->contactMaster->save('contact', $data_contact);
                            $id_contact_slave = $this->contactMaster->getLastId();
                            $id_customer = $this->customerSession->getCustomer()->getId();
                            $id_contact_main = $this->contactMaster->getContactByExtID($id_customer)['contact_id'];


                            // CONTACT ADDRESS
                            if($post['contact_' . $i]['city']!='' || $post['contact_' . $i]['zip_code']!= ''){
                                $data_address[] = array(
                                    'city'       => isset($post['contact_' . $i]['city'])     ? $post['contact_' . $i]['city']     : '',
                                    'zip_code'   => isset($post['contact_' . $i]['zip_code']) ? $post['contact_' . $i]['zip_code'] : '',
                                    'contact_id' => $this->contactMaster->getLastId(),
                                    'created_at' => date('Y-m-d H:i:s')
                                );
                                $this->contactMaster->save('contact_address', $data_address);
                            }

                            // SPONSOR
                            if(isset($id_contact_slave)) {
                                $data_sponsor[] = array(
                                    //                                'main_contact_sponsor' => $post['main_contact_sponsor'],
                                    'main_contact_sponsor'  => 4231,
                                    'slave_contact_sponsor' => $id_contact_slave,
                                    'type_id'               => 2,
                                    'type_godfather'        => $post['type_godfather'],
                                    'params'                => !empty($post['contact_' . $i]['params']) ? $post['contact_' . $i]['params'] : '',
                                    'created_at'            => date('Y-m-d H:i:s')
                                );
                                $this->contactMaster->save('contact_sponsor', $data_sponsor);
                            }

                            // SUCCESS
                            $_SESSION['message']['addSuccess'] = 'Sponsor bien ajouté !';
                        } else {
                            // ERROR
                            $_SESSION['message']['addError'] = $this->formRules($post['contact_' . $i]);
                        }
                    } else {
                        $_SESSION['message']['addError'] = $post['contact_' . $i]['firstname']." ".$post['contact_' . $i]['lastname']." existe déjà !";
                    }
                }
            }
        }
    }

    /**
     * Check Rules for form add sponsor
     *
     * @return error (array)
     */
    private function formRules($post) {
        $valid = true;
        $message = '';

        $patternMail = "/^[a-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,3}$/";
        $patternTel = '/^0[123456789][\s-]?([0-9]{2}[\s-]?){4}$/';
        $patternCp = '/^\d{5}$/';

        if (is_int($post['lastname']) || is_int($post['firstname'])) {
            $valid = false;
            $message = 'Le nom ou le prénom ne doit pas contenir de chiffres';
        }

        if (is_string( (int) $post['phone'])) {
            $valid = false;
            $message = 'Le téléphone ne doit pas contenir de caractères !';
        }

        if (strlen($post['phone']) < 10) {
            $valid = false;
            $message = 'Un numéro de téléphone contient au moins 10 chiffres !';
        }

        if (preg_match($patternTel, $post['phone']) == 0) {
            $valid = false;
            $message = 'Un numéro de téléphone téléphone doit commencer par 01, 02, 03, 04, 05, 06, 07, 08 ou 09 !';
        }

        if ($post['email'] != '' && !preg_match($patternMail, $post['email'])) {
            $valid = false;
            $message = 'l\'adresse email est incorrecte';
        }

        if ($post['zip_code'] != '' && !preg_match($patternCp, $post['zip_code'])) {
            $valid = false;
            $message = 'le code postal saisie doit contenir 5 chiffres';
        }


        if ($valid == false) {
            return $message;
        } else {
            return $valid;
        }
    }

}
