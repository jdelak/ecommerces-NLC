<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.1.2
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use SolideWebservices\Flexslider\Model\ResourceModel\Group\CollectionFactory;

class Group extends Column
{
    /**
     * Variable.
     *
     * @var CollectionFactory
     */
    protected $_groupCollectionFactory;

    /**
     * Variable.
     *
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Variable.
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Construct.
     *
     * @param ContextInterface                $context                Context.
     * @param UiComponentFactory              $uiComponentFactory     UIComponentFactory.
     * @param CollectionFactory               $groupCollectionFactory GroupCollectionFactory.
     * @param \Magento\Framework\UrlInterface $urlBuilder             URLBuilder.
     * @param StoreManagerInterface           $storeManager           StoreManager.
     * @param array                           $components             Components.
     * @param array                           $data                   Data.
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CollectionFactory $groupCollectionFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->_groupCollectionFactory = $groupCollectionFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource DataSource.
     *
     * @return $datasource[]
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {

                $groupCollection = $this->_groupCollectionFactory
                    ->create()->addSlideFilter($item['slide_id']);
                $html = '<ul style="margin-bottom: 0px;">';

                if ($groupCollection) {
                    foreach ($groupCollection as $group) {
                        $html .= '<li>'. $group['title'] . ' (id:'. $group['group_id'] .')</li>';
                    }
                } else {
                    $html .= '<li>'. __('No Group(s)') .'</li>';
                }

                $html .= '</ul>';
                $item[$fieldName] = $html;

            }
        }

        return $dataSource;
    }

    /**
     * Get Alt Row.
     *
     * @param array $row Row.
     *
     * @return string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
