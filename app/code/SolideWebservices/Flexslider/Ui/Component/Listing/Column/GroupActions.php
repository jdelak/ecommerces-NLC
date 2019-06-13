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

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class PageActions
 */
class GroupActions extends Column
{
    const GROUP_URL_PATH_EDIT = 'flexslider/group/edit';
    const GROUP_URL_PATH_DELETE = 'flexslider/group/delete';

    /**
     * Variable.
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Variable.
     *
     * @var string
     */
    private $editUrl;

    /**
     * Construct.
     *
     * @param ContextInterface   $context            Context.
     * @param UiComponentFactory $uiComponentFactory UIComponentFactory.
     * @param UrlInterface       $urlBuilder         UrlBuilder.
     * @param array              $components         Compontents.
     * @param array              $data               Data.
     * @param string             $editUrl            EditURL.
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::GROUP_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource Data Source.
     *
     * @return datasource[]
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['group_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            $this->editUrl,
                            ['group_id' => $item['group_id']]
                        ),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::GROUP_URL_PATH_DELETE,
                            ['group_id' => $item['group_id']]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => sprintf(
                                __('Delete Flexslider Group "%s"'),
                                $item['title']
                            ),
                            'message' => sprintf(
                                __('Are you sure you want to delete group "%s"?'),
                                $item['title']
                            )
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }

}
