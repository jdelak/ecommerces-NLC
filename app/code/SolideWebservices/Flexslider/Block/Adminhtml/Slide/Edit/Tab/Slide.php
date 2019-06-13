<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.4
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Block\Adminhtml\Slide\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config;
use SolideWebservices\Flexslider\Helper\Options;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;
use SolideWebservices\Flexslider\Model\GroupFactory;

/**
 * Flexslider Slide form block
 */
class Slide extends \Magento\Backend\Block\Widget\Form\Generic implements
\Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Variable.
     *
     * @var FieldFactory
     */
    protected $fieldFactory;

    /**
     * Variable.
     *
     * @var Config
     */
    protected $wysiwygConfig;

    /**
     * Variable.
     *
     * @var Options
     */
    protected $flexsliderHelper;

    /**
     * Construct.
     *
     * @param Context      $context          Context.
     * @param Config       $wysiwygConfig    WYSIWYGConfig.
     * @param Options      $flexsliderHelper FlexsliderHelper.
     * @param Registry     $registry         Registry.
     * @param FormFactory  $formFactory      FormFactory.
     * @param FieldFactory $fieldFactory     FieldFactory.
     * @param GroupFactory $groupFactory     FormFactory.
     * @param array        $data             Data.
     */
    public function __construct(
        Context $context,
        Config $wysiwygConfig,
        Options $flexsliderHelper,
        Registry $registry,
        FormFactory $formFactory,
        FieldFactory $fieldFactory,
        GroupFactory $groupFactory,
        array $data = []
    ) {
        $this->_localeDate = $context->getLocaleDate();
        $this->wysiwygConfig = $wysiwygConfig;
        $this->flexsliderHelper = $flexsliderHelper;
        $this->fieldFactory = $fieldFactory;
        $this->_groupFactory = $groupFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('flexslider_slide');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');
        $dependenceBlock = $this->getLayout()
        ->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence');
        $fieldMaps = [];
        $mode = '';

        $fieldset = $form->addFieldset(
            'general_fieldset',
            ['legend' => __('General')]
        );

        $wysiwygConfig = $this->wysiwygConfig
                            ->getConfig(['tab_id' => $this->getTabId()]);

        if ($model->getId()) {
            $fieldMaps['slide_id'] = $fieldset->addField(
                                        'slide_id',
                                        'hidden',
                                        ['name' => 'slide_id']
                                    );
            $mode = 'false';
        }

        $fieldMaps['group_id'] = $fieldset->addField(
            'group_id',
            'multiselect',
            [
            'name' => 'groups[]',
            'label' => __('Groups'),
            'title' => __('Groups'),
            'required' => true,
            'style' => 'width:400px',
            'values' => $model->getAvailableGroups(),
            ]
        );

        $fieldMaps['title'] = $fieldset->addField(
            'title',
            'text',
            [
            'name' => 'title',
            'label' => __('Title'),
            'title' => __('Title'),
            'required' => true,
            'class' => 'required-entry',
            ]
        );

        $fieldMaps['identifier'] = $fieldset->addField(
            'identifier',
            'text',
            [
            'name' => 'identifier',
            'label' => __('Identifier'),
            'title' => __('Identifier'),
            'note' => __('a unique identifier that is used to identify a
                        slide'),
            'required' => true,
            'class'    => 'required-entry validate-code',
            ]
        );

        $fieldMaps['is_active'] = $fieldset->addField(
            'is_active',
            'select',
            [
            'name' => 'is_active',
            'label' => __('Status'),
            'title' => __('Status'),
            'required' => true,
            'options' => $model->getAvailableStatuses()
            ]
        );

        $fieldMaps['slide_sort_order'] = $fieldset->addField(
            'slide_sort_order',
            'text',
            [
            'name' => 'slide_sort_order',
            'label' => __('Sort Order'),
            'title' => __('Sort Order'),
            'note' => __('set the sort order of the slide in the selected
                        slider group(s)'),
            'required' => false,
            'class' => 'validate-number validate-greater-than-zero',
            ]
        );

        $fieldMaps['slide_loggedin'] = $fieldset->addField(
            'slide_loggedin',
            'select',
            [
            'name' => 'slide_loggedin',
            'label' => __('Visible to logged in customers only?'),
            'title' => __('Visible to logged in customers only?'),
            'required' => false,
            'values' => $this->flexsliderHelper->getYesNoArray(),
            ]
        );

        $fieldMaps['slide_startdate'] = $fieldset->addField(
            'slide_startdate',
            'date',
            [
            'name' => 'slide_startdate',
            'label' => __('Slide Start Date'),
            'title' => __('Slide Start Date'),
            'note' => __('leave empty to always show this slide'),
            'required' => false,
            'time' => true,
            'date_format' => 'yyyy-MM-dd',
            'time_format' => 'HH:mm:ss',
            ]
        );

        $fieldMaps['slide_enddate'] = $fieldset->addField(
            'slide_enddate',
            'date',
            [
            'name' => 'slide_enddate',
            'label' => __('Slide End Date'),
            'title' => __('Slide End Date'),
            'note' => __('leave empty to always show this slide'),
            'required' => false,
            'time' => true,
            'date_format' => 'yyyy-MM-dd',
            'time_format' => 'HH:mm:ss',
            ]
        );

        $fieldset = $form->addFieldset(
            'config_fieldset',
            ['legend' => __('Slide Configuration')]
        );
        $fieldset->addType('image', '\SolideWebservices\Flexslider\Block\Adminhtml\Slide\Helper\Image');

        $fieldMaps['slide_type'] = $fieldset->addField(
            'slide_type',
            'select',
            [
            'name' => 'slide_type',
            'label' => __('Image or Video'),
            'title' => __('Image or Video'),
            'required' => false,
            'values' => $this->flexsliderHelper->getSlideTypeArray(),
            'disabled'     => $mode,
            ]
        );

        $fieldMaps['hosted_image'] = $fieldset->addField(
            'hosted_image',
            'select',
            [
            'name' => 'hosted_image',
            'label' => __('Use External Image Hosting '),
            'title' => __('Use External Image Hosting '),
            'note' => __('instead of uploading images you can host your images
                        on a image hoster and just enter the link to the image
                        and thumbnail'),
            'required' => false,
            'values' => $this->flexsliderHelper->getYesNoArray(),
            'disabled'     => $mode,
            ]
        );

        $fieldMaps['hosted_image_url'] = $fieldset->addField(
            'hosted_image_url',
            'text',
            [
                'name' => 'hosted_image_url',
                'label' => __('Hosted Image URL'),
                'title' => __('Hosted Image URL'),
                'required' => false,
            ]
        );

        $fieldMaps['hosted_image_thumburl'] = $fieldset->addField(
            'hosted_image_thumburl',
            'text',
            [
            'name' => 'hosted_image_thumburl',
            'label' => __('Hosted Image Thumbnail URL'),
            'title' => __('Hosted Image Thumbnail URL'),
            'note' => __('you can use the same URL as above but for performance
                        reasons it\'s better to upload a seperate small
                        thumbnail of this image, the thumbnails are used in
                        carousels'),
            'required' => false,
            ]
        );

        $fieldMaps['image'] = $fieldset->addField(
            'image',
            'image',
            [
            'name' => 'image',
            'label' => __('Image'),
            'title' => __('Image'),
            'note' => __('Allowed image types: jpg, jpeg, gif, png'),
            'required' => true,
            ]

        );

        $fieldMaps['alt_text'] = $fieldset->addField(
            'alt_text',
            'text',
            [
            'name' => 'alt_text',
            'label' => __('Image ALT text'),
            'title' => __('Image ALT text'),
            'required' => false,
            ]
        );

        $fieldMaps['url'] = $fieldset->addField(
            'url',
            'text',
            [
            'name' => 'url',
            'label' => __('OnClick URL'),
            'title' => __('OnClick URL'),
            'required' => false,
            ]
        );

        $fieldMaps['url_target'] = $fieldset->addField(
            'url_target',
            'select',
            [
            'name' => 'url_target',
            'label' => __('OnClick URL Target'),
            'title' => __('OnClick URL Target'),
            'required' => false,
            'values' => $this->flexsliderHelper->getUrlTargetArray(),
            ]
        );

        $fieldMaps['video_id'] = $fieldset->addField(
            'video_id',
            'text',
            [
            'name' => 'video_id',
            'label' => __('Video ID'),
            'title' => __('Video ID'),
            'note' => __('enter the video id of your YouTube or Vimeo video
                        (not the full link)'),
            'required' => true,
            ]
        );

        $fieldMaps['video_autoplay'] = $fieldset->addField(
            'video_autoplay',
            'select',
            [
            'name' => 'video_autoplay',
            'label' => __('Autoplay Video?'),
            'title' => __('Autoplay Video?'),
            'note' => __('pause the slider and start the video automatically'),
            'required' => false,
            'values' => $this->flexsliderHelper->getYesNoArray(),
            ]
        );

        $fieldset = $form->addFieldset(
            'appearance_fieldset',
            ['legend' => __('Appearance')]
        );

        $fieldMaps['caption_html'] = $fieldset->addField(
            'caption_html',
            'editor',
            [
            'name' => 'caption_html',
            'label' => __('Caption'),
            'title' => __('Caption'),
            'style' => 'height:16em;',
            'required' => false,
            'config' => $wysiwygConfig
            ]
        );

        $fieldMaps['caption_position'] = $fieldset->addField(
            'caption_position',
            'select',
            [
            'name' => 'caption_position',
            'label' => __('Caption Position'),
            'title' => __('Caption Position'),
            'required' => false,
            'values' => $this->flexsliderHelper->getCaptionPositionsArray(),
            ]
        );

        $fieldMaps['caption_animation'] = $fieldset->addField(
            'caption_animation',
            'select',
            [
            'name' => 'caption_animation',
            'label' => __('Caption Animation'),
            'title' => __('Caption Animation'),
            'note' => __('should the caption animate into the slide'),
            'required' => false,
            'values' => $this->flexsliderHelper->getYesNoArray(),
            ]
        );


        foreach ($fieldMaps as $fieldMap) {
            $dependenceBlock->addFieldMap(
                $fieldMap->getHtmlId(),
                $fieldMap->getName()
            );
        }

        $mappingFieldDependence = $this->getMappingFieldDependence();

        foreach ($mappingFieldDependence as $dep) {
            $negative = isset($dep['negative']) && $dep['negative'];
            if (is_array($dep['fieldName'])) {
                foreach ($dep['fieldName'] as $fieldName) {
                    $dependenceBlock->addFieldDependence(
                        $fieldMaps[$fieldName]->getName(),
                        $fieldMaps[$dep['fieldNameFrom']]->getName(),
                        $this->getDependencyField($dep['refField'], $negative)
                    );
                }
            } else {
                $dependenceBlock->addFieldDependence(
                    $fieldMaps[$dep['fieldName']]->getName(),
                    $fieldMaps[$dep['fieldNameFrom']]->getName(),
                    $this->getDependencyField($dep['refField'], $negative)
                );
            }
        }

        $this->setChild('form_after', $dependenceBlock);

        $defaultData = [
            'is_active' => 1,
            'slide_loggedin' => 0,
            'slide_sort_order' => 1,
            'hosted_image' => 0,
            'url_target' => '_self',
        ];

        if (!$model->getId()) {
            $model->addData($defaultData);
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Get field dependencies.
     *
     * @param string $refField    Reference field for field dependency.
     * @param bool   $negative    Invert selection of reference.
     * @param string $separator   Field seperator.
     * @param string $fieldPrefix Field prefix.
     *
     * @return $this->fieldFactory
     */
    public function getDependencyField(
        $refField,
        $negative = false,
        $separator = ',',
        $fieldPrefix = ''
    ) {
        return $this->fieldFactory->create(
            ['fieldData' =>
                [
                'value' => (string) $refField,
                'negative' => $negative,
                'separator' => $separator
                ],
                'fieldPrefix' => $fieldPrefix
            ]
        );
    }

    /**
     * Get mapping of field dependencies.
     *
     * @return getMappingFieldDependence[] Array of field dependencies.
     */
    public function getMappingFieldDependence()
    {
        return [
            [
                'fieldName' => [
                                'hosted_image',
                                'hosted_image_url',
                                'hosted_image_thumburl',
                                'image', 'alt_text',
                                'url',
                                'url_target'
                            ],
                'fieldNameFrom' => 'slide_type',
                'refField' => 'image',
            ],
            [
                'fieldName' => ['image'],
                'fieldNameFrom' => 'hosted_image',
                'refField' => '0',
            ],
            [
                'fieldName' => ['hosted_image_url', 'hosted_image_thumburl'],
                'fieldNameFrom' => 'hosted_image',
                'refField' => '1',
            ],
            [
                'fieldName' => ['video_id', 'video_autoplay'],
                'fieldNameFrom' => 'slide_type',
                'refField' => implode(',', [
                    'youtube',
                    'vimeo',
                ]),
            ],
        ];
    }

    /**
     * Prepare label for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Slide Settings');
    }

    /**
     * Prepare title for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Slide Settings');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

}
