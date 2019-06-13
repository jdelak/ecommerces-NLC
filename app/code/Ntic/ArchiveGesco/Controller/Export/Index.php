<?php


namespace Ntic\ArchiveGesco\Controller\Export;
use Ntic\ArchiveGesco\Helper\HandlerArchiveGesco;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);

        // Appel helper "HandlerGesco"
        $this->handler_gesco = \Magento\Framework\App\ObjectManager::getInstance()->get("\Ntic\ArchiveGesco\Helper\HandlerGesco");
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        // Chemin du fichier et nom du fichier
        $file = $_SERVER['DOCUMENT_ROOT']."/var/tmp/export-archive-gesco.csv";
        $fileName = 'export-all-archive-gesco.csv';

        if(!file_exists($file)) {
            // Requete SQL et crétion du fichier avec SQL
            $this->handler_gesco->exportUsers($file);
        } else {
            header("Content-disposition: attachment; filename=".$fileName."");
            readfile($file);
            exit;
        }

        if(file_exists($file)) {
            unlink($file);
        }

        $this->_redirect('archivegesco');
    }
}
