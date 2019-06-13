<?php
/**
 * SolideWebservices/Flexslider
 *
 * @category Magento2_Module
 * @package  Flexslider
 * @author   Solide Webservices <contact@solidewebservices.com>
 * @license  https://opensource.org/licenses/OSL-3.0 Open Software License 3.0
 * @version  2.2.0
 * @link     https://solidewebservices.com
 */

namespace SolideWebservices\Flexslider\Block\Adminhtml\Group\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use SolideWebservices\Flexslider\Helper\Options;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;

/**
 * Flexslider Group form block
 */
class Group extends \Magento\Backend\Block\Widget\Form\Generic implements
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
     * @var Store
     */
    protected $systemStore;

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
     * @param Options      $flexsliderHelper Helper.
     * @param Registry     $registry         Registry.
     * @param FormFactory  $formFactory      Context.
     * @param Store        $systemStore      Systemstore.
     * @param FieldFactory $fieldFactory     FormFactory.
     * @param array        $data             Data.
     */
    public function __construct(
        Context $context,
        Options $flexsliderHelper,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        FieldFactory $fieldFactory,
        array $data = []
    ) {
        $this->_localeDate = $context->getLocaleDate();
        $this->systemStore = $systemStore;
        $this->flexsliderHelper = $flexsliderHelper;
        $this->fieldFactory = $fieldFactory;
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
        $model = $this->_coreRegistry->registry('flexslider_group');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('flexslider_');
        $dependenceBlock = $this->getLayout()
        ->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence');
        $fieldMaps = [];

        $fieldset = $form->addFieldset(
                        'general_fieldset',
                        ['legend' => __('General')]
                    );
        $fieldset->addType(
            'groupposition',
            'SolideWebservices\Flexslider\Data\Form\Element\Groupposition'
        );

        if ($model->getId()) {
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        }

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
            'note' => __('a unique identifier that is used to
                        inject the slide group via XML'),
            'required' => true,
            'class' => 'required-entry validate-code',
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

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                'name' => 'stores[]',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'values' => $this->systemStore
                            ->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this
                ->getLayout()
                ->createBlock('Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element');
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                [
                'name' => 'stores[]',
                'value' => $this->_storeManager->getStore(true)->getId()
                ]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldMaps['group_position'] = $fieldset->addField(
            'group_position',
            'groupposition',
            [
            'name' => 'group_position',
            'label' => __('Position'),
            'title' => __('Position'),
            'required' => true,
            'values' => $this->flexsliderHelper->getGroupPositionsArray(),
            ]
        );

        $fieldMaps['group_sort_order'] = $fieldset->addField(
            'group_sort_order',
            'text',
            [
            'name' => 'group_sort_order',
            'label' => __('Sort Order'),
            'title' => __('Sort Order'),
            'note' => __('set the sort order in case of
                        multiple groups on one page'),
            'required' => false,
            'class' => 'validate-number validate-greater-than-zero',
            ]
        );

        $fieldMaps['group_random_slides'] = $fieldset->addField(
            'group_random_slides',
            'select',
            [
            'name' => 'group_random_slides',
            'label' => __('Random Order'),
            'title' => __('Random Order'),
            'note' => __('set the sort order of the slides as random (this
                        ignores the sort order configured for the slides)'),
            'required' => false,
            'values' => $this->flexsliderHelper->getYesNoArray(),
            ]
        );

        $fieldMaps['group_loggedin'] = $fieldset->addField(
            'group_loggedin',
            'select',
            [
            'name' => 'group_loggedin',
            'label' => __('Visible to logged in customers only?'),
            'title' => __('Visible to logged in customers only?'),
            'required' => false,
            'values' => $this->flexsliderHelper->getYesNoArray(),
            ]
        );

        $fieldMaps['group_startdate'] = $fieldset->addField(
            'group_startdate',
            'date',
            [
            'name' => 'group_startdate',
            'label' => __('Group Start Date'),
            'title' => __('Group Start Date'),
            'note' => __('leave empty to always show this group'),
            'required' => false,
            'time' => true,
            'date_format' => 'yyyy-MM-dd',
            'time_format' => 'HH:mm:ss',
            ]
        );

        $fieldMaps['group_enddate'] = $fieldset->addField(
            'group_enddate',
            'date',
            [
            'name' => 'group_enddate',
            'label' => __('Group End Date'),
            'title' => __('Group End Date'),
            'note' => __('leave empty to always show this group'),
            'required' => false,
            'time' => true,
            'date_format' => 'yyyy-MM-dd',
            'time_format' => 'HH:mm:ss',
            ]
        );

        $fieldset = $form->addFieldset(
                        'appearance_fieldset',
                        ['legend' => __('Appearance')]
                    );

        $fieldMaps['group_type'] = $fieldset->addField(
            'group_type',
            'select',
            [
            'name' => 'group_type',
            'label' => __('Group Type'),
            'title' => __('Group Type'),
            'required' => true,
            'values' => $this->flexsliderHelper->getGroupTypeArray(),
            ]
        );

        $fieldMaps['thumbnail_size'] = $fieldset->addField(
            'thumbnail_size',
            'text',
            [
            'name' => 'thumbnail_size',
            'label' => __('Thumbnail Width'),
            'title' => __('Thumbnail Width'),
            'note' => __('width of the thumbnails in carousel, should not be
                        larger then thumbnail upload width in general settings
                        (default is 200)'),
            'required' => false,
            'class' => 'validate-number',
            ]
        );

        $fieldMaps['carousel_minitems'] = $fieldset->addField(
            'carousel_minitems',
            'text',
            [
            'name' => 'carousel_minitems',
            'label' => __('Carousel Minimum Items'),
            'title' => __('Carousel Minimum Items'),
            'note' => __('minimum number of carousel items that should be
                        visible, items will resize fluidly when below this'),
            'required' => true,
            'class' => 'validate-number',
            ]
        );

        $fieldMaps['carousel_maxitems'] = $fieldset->addField(
            'carousel_maxitems',
            'text',
            [
            'name' => 'carousel_maxitems',
            'label' => __('Carousel Maximum Items'),
            'title' => __('Carousel Maximum Items'),
            'note' => __('maximum number of carousel items that should be
                        visible, items will resize fluidly when above
                        this limit'),
            'required' => true,
            'class' => 'validate-number',
            ]
        );

        $fieldMaps['carousel_move'] = $fieldset->addField(
            'carousel_move',
            'text',
            [
            'name' => 'carousel_move',
            'label' => __('Carousel Move Items'),
            'title' => __('Carousel Move Items'),
            'note' => __('number of carousel items that should move on
                        animation, if 0 then the slider will move all visible
                        items'),
            'required' => true,
            'class' => 'validate-number',
            ]
        );

        $fieldMaps['overlay_position'] = $fieldset->addField(
            'overlay_position',
            'select',
            [
            'name' => 'overlay_position',
            'label' => __('Overlay Position'),
            'title' => __('Overlay Position'),
            'required' => false,
            'values' => $this->flexsliderHelper->getOverlayPositionArray(),
            ]
        );

        $fieldMaps['overlay_textcolor'] = $fieldset->addField(
            'overlay_textcolor',
            'text',
            [
            'name' => 'overlay_textcolor',
            'label' => __('Overlay Text Color'),
            'title' => __('Overlay Text Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldMaps['overlay_bgcolor'] = $fieldset->addField(
            'overlay_bgcolor',
            'text',
            [
            'name' => 'overlay_bgcolor',
            'label' => __('Overlay Background Color'),
            'title' => __('Overlay Background Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px'
            ]
        );

        $fieldMaps['overlay_hovercolor'] = $fieldset->addField(
            'overlay_hovercolor',
            'text',
            [
            'name' => 'overlay_hovercolor',
            'label' => __('Overlay Hover Color'),
            'title' => __('Overlay Hover Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px'
            ]
        );

        $fieldMaps['theme'] = $fieldset->addField(
            'theme',
            'select',
            [
            'name' => 'theme',
            'label' => __('Theme'),
            'title' => __('Theme'),
            'required' => true,
            'values' => $this->flexsliderHelper->getThemeArray(),
            ]
        );

        $fieldMaps['custom_theme'] = $fieldset->addField(
            'custom_theme',
            'textarea',
            [
            'name' => 'custom_theme',
            'label' => __('Custom CSS'),
            'title' => __('Custom CSS'),
            'note' => __('enter your custom css here'),
            'required' => false
            ]
        );

        $fieldMaps['width'] = $fieldset->addField(
            'width',
            'text',
            [
            'name' => 'width',
            'label' => __('Group Width'),
            'title' => __('Group Width'),
            'note' => __('maximum width of the slider group in pixels, leave
                        empty or 0 for full responsive width'),
            'required' => false,
            'class' => 'validate-number',
            ]
        );

        $fieldMaps['group_smoothheight'] = $fieldset->addField(
            'group_smoothheight',
            'select',
            [
            'name' => 'group_smoothheight',
            'label' => __('Smooth Height Adjustment'),
            'title' => __('Smooth Height Adjustment'),
            'note' => __('allow slider group to scale/adjust height depending
                        on height slides'),
            'required' => true,
            'values' => $this->flexsliderHelper->getYesNoArray()
            ]
        );

        $fieldset = $form->addFieldset(
                        'navigation_fieldset',
                        ['legend' => __('Navigation')]
                    );

        $fieldMaps['nav_show'] = $fieldset->addField(
            'nav_show',
            'select',
            [
            'name' => 'nav_show',
            'label' => __('Show Navigation Arrows'),
            'title' => __('Show Navigation Arrows'),
            'required' => true,
            'values' => $this->flexsliderHelper
                            ->getNavShowPaginationShowArray(),
            ]
        );

        $fieldMaps['nav_style'] = $fieldset->addField(
            'nav_style',
            'select',
            [
            'name' => 'nav_style',
            'label' => __('Navigation Arrows Style'),
            'title' => __('Navigation Arrows Style'),
            'required' => false,
            'values' => $this->flexsliderHelper->getNavStyleArray(),
            'after_element_html' => $this->flexsliderHelper
                                        ->getExampleNavStyles(),
            ]
        );

        $fieldMaps['nav_size'] = $fieldset->addField(
            'nav_size',
            'select',
            [
            'name' => 'nav_size',
            'label' => __('Navigation Arrows Size'),
            'title' => __('Navigation Arrows Size'),
            'required' => false,
            'values' => $this->flexsliderHelper->getNavSizeArray(),
            ]
        );

        $fieldMaps['nav_position'] = $fieldset->addField(
            'nav_position',
            'select',
            [
            'name' => 'nav_position',
            'label' => __('Navigation Arrows Position'),
            'title' => __('Navigation Arrows Position'),
            'required' => false,
            'values' => $this->flexsliderHelper->getNavPositionArray(),
            ]
        );

        $fieldMaps['nav_color'] = $fieldset->addField(
            'nav_color',
            'text',
            [
            'name' => 'nav_color',
            'label' => __('Navigation Arrows Color'),
            'title' => __('Navigation Arrows Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldMaps['nav_note'] = $fieldset->addField(
            'nav_note',
            'note',
            [
            'name' => 'nav_note',
            'note' => __('Navigation settings are not available for the group
                        type with overlay navigation'),
            ]
        );

        $fieldset = $form->addFieldset(
                        'pagination_fieldset',
                        ['legend' => __('Pagination')]
                    );

        $fieldMaps['pagination_show'] = $fieldset->addField(
            'pagination_show',
            'select',
            [
            'name' => 'pagination_show',
            'label' => __('Show Pagination'),
            'title' => __('Show Pagination'),
            'required' => true,
            'values' => $this->flexsliderHelper
                            ->getNavShowPaginationShowArray(),
            ]
        );

        $fieldMaps['pagination_style'] = $fieldset->addField(
            'pagination_style',
            'select',
            [
            'name' => 'pagination_style',
            'label' => __('Pagination Style'),
            'title' => __('Pagination Style'),
            'required' => false,
            'values' => $this->flexsliderHelper->getPaginationStyleArray(),
            ]
        );

        $fieldMaps['pagination_position'] = $fieldset->addField(
            'pagination_position',
            'select',
            [
            'name' => 'pagination_position',
            'label' => __('Pagination Position'),
            'title' => __('Pagination Position'),
            'required' => false,
            'values' => $this->flexsliderHelper->getPaginationPositionArray(),
            ]
        );

        $fieldMaps['pagination_color'] = $fieldset->addField(
            'pagination_color',
            'text',
            [
            'name' => 'pagination_color',
            'label' => __('Pagination Color'),
            'title' => __('Pagination Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldMaps['pagination_hover_color'] = $fieldset->addField(
            'pagination_hover_color',
            'text',
            [
            'name' => 'pagination_hover_color',
            'label' => __('Pagination Hover Color'),
            'title' => __('Pagination Hover Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldMaps['pagination_active_color'] = $fieldset->addField(
            'pagination_active_color',
            'text',
            [
            'name' => 'pagination_active_color',
            'label' => __('Pagination Active Color'),
            'title' => __('Pagination Active Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldMaps['pagination_note'] = $fieldset->addField(
            'pagination_note',
            'note',
            [
            'name' => 'pagination_note',
            'note' => __('Pagination settings are not available for the group
                        type with overlay navigation'),
            ]
        );

        $fieldset = $form->addFieldset(
                        'loader_fieldset',
                        ['legend' => __('Loader (Progressbar)')]
                    );

        $fieldMaps['loader_show'] = $fieldset->addField(
            'loader_show',
            'select',
            [
            'name' => 'loader_show',
            'label' => __('Show Loader'),
            'title' => __('Show Loader'),
            'required' => true,
            'values' => $this->flexsliderHelper->getYesNoArray(),
            ]
        );

        $fieldMaps['loader_position'] = $fieldset->addField(
            'loader_position',
            'select',
            [
            'name' => 'loader_position',
            'label' => __('Loader Position'),
            'title' => __('Loader Position'),
            'required' => false,
            'values' => $this->flexsliderHelper->getLoaderPositionArray(),
            ]
        );

        $fieldMaps['loader_color'] = $fieldset->addField(
            'loader_color',
            'text',
            [
            'name' => 'loader_color',
            'label' => __('Loader Color'),
            'title' => __('Loader Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldMaps['loader_bgcolor'] = $fieldset->addField(
            'loader_bgcolor',
            'text',
            [
            'name' => 'loader_bgcolor',
            'label' => __('Loader Gutter Color'),
            'title' => __('Loader Gutter Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldset = $form->addFieldset(
                        'caption_fieldset',
                        ['legend' => __('Caption')]
                    );

        $fieldset->addField(
            'caption_note',
            'note',
            [
            'note' => __('The caption is set per slide but these settings
                        control their appearance'),
            ]
        );

        $fieldMaps['caption_textcolor'] = $fieldset->addField(
            'caption_textcolor',
            'text',
            [
            'name' => 'caption_textcolor',
            'label' => __('Caption Default Textcolor'),
            'title' => __('Caption Default Textcolor'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldMaps['caption_bgcolor'] = $fieldset->addField(
            'caption_bgcolor',
            'text',
            [
            'name' => 'caption_bgcolor',
            'label' => __('Caption Background Color'),
            'title' => __('Caption Background Color'),
            'required' => false,
            'class' => 'colorpicker',
            'style' => 'width: 196px; height: 30px',
            ]
        );

        $fieldset = $form->addFieldset(
                        'effects_fieldset',
                        ['legend' => __('Effects')]
                    );

        $fieldMaps['group_animation'] = $fieldset->addField(
            'group_animation',
            'select',
            [
            'name' => 'group_animation',
            'label' => __('Animation Type'),
            'title' => __('Animation Type'),
            'required' => true,
            'values' => $this->flexsliderHelper->getGroupAnimationArray(),
            ]
        );

        $fieldMaps['group_animation_direction'] = $fieldset->addField(
            'group_animation_direction',
            'select',
            [
            'name' => 'group_animation_direction',
            'label' => __('Animation Direction'),
            'title' => __('Animation Direction'),
            'required' => true,
            'values' => $this->flexsliderHelper
                                ->getGroupAnimationDirectionArray(),
            ]
        );

        $fieldMaps['group_animation_reverse'] = $fieldset->addField(
            'group_animation_reverse',
            'select',
            [
            'name' => 'group_animation_reverse',
            'label' => __('Animation Reverse'),
            'title' => __('Animation Reverse'),
            'required' => true,
            'values' => $this->flexsliderHelper->getYesNoArray()
            ]
        );

        $fieldMaps['group_animation_duration'] = $fieldset->addField(
            'group_animation_duration',
            'text',
            [
            'name' => 'group_animation_duration',
            'label' => __('Animation Duration'),
            'title' => __('Animation Duration'),
            'note' => __('in milliseconds (default is 600)'),
            'required' => true,
            'class' => 'validate-number'
            ]
        );

        $fieldMaps['group_animation_easing'] = $fieldset->addField(
            'group_animation_easing',
            'select',
            [
            'name' => 'group_animation_easing',
            'label' => __('Animation Easing'),
            'title' => __('Animation Easing'),
            'required' => true,
            'values' => $this->flexsliderHelper->getGroupAnimationEasingArray()
            ]
        );

        $fieldMaps['group_autoslide'] = $fieldset->addField(
            'group_autoslide',
            'select',
            [
            'name' => 'group_autoslide',
            'label' => __('Auto Slide'),
            'title' => __('Auto Slide'),
            'required' => true,
            'values' => $this->flexsliderHelper->getYesNoArray()
            ]
        );

        $fieldMaps['group_pauseonaction'] = $fieldset->addField(
            'group_pauseonaction',
            'select',
            [
            'name' => 'group_pauseonaction',
            'label' => __('Stop Auto Slide On Navigation'),
            'title' => __('Stop Auto Slide On Navigation'),
            'required' => true,
            'values' => $this->flexsliderHelper->getYesNoArray()
            ]
        );

        $fieldMaps['group_pauseonhover'] = $fieldset->addField(
            'group_pauseonhover',
            'select',
            [
            'name' => 'group_pauseonhover',
            'label' => __('Pause Group On Hover'),
            'title' => __('Pause Group On Hover'),
            'required' => true,
            'values' => $this->flexsliderHelper->getYesNoArray()
            ]
        );

        $fieldMaps['group_autoloop'] = $fieldset->addField(
            'group_autoloop',
            'select',
            [
            'name' => 'group_autoloop',
            'label' => __('Loop Slides In Group'),
            'title' => __('Loop Slides In Group'),
            'required' => true,
            'values' => $this->flexsliderHelper->getYesNoArray()
            ]
        );

        $fieldMaps['group_slide_duration'] = $fieldset->addField(
            'group_slide_duration',
            'text',
            [
            'name' => 'group_slide_duration',
            'label' => __('Slide Duration'),
            'title' => __('Slide Duration'),
            'note' => __('in milliseconds (default is 7000)'),
            'required' => true,
            'class' => 'validate-number'
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
            'group_sort_order' => 1,
            'group_random_slides' => 0,
            'group_loggedin' => 0,
            'carousel_minitems' => 3,
            'carousel_maxitems' => 5,
            'carousel_move' => 0,
            'overlay_textcolor' => '#ffffff',
            'overlay_bgcolor' => 'rgba(34, 34, 34, 0.8)',
            'overlay_hovercolor' => 'rgba(102, 102, 102, 0.8)',
            'group_theme' => 'basic',
            'thumbnail_size' => '200',
            'group_smoothheight' => 1,
            'nav_show' => 'hover',
            'nav_style' => 'type-1',
            'nav_size' => 'normal',
            'nav_position' => 'inside',
            'nav_color' => '#666666',
            'pagination_show' => 'always',
            'pagination_style' => 'circular',
            'pagination_position' => 'below',
            'pagination_color' => '#ffffff',
            'pagination_hover_color' => '#293f67',
            'pagination_active_color' => '#cccccc',
            'loader_show' => 1,
            'loader_position' => 'top',
            'loader_color' => '#eeeeee',
            'loader_bgcolor' => '#222222',
            'caption_textcolor' => '#ffffff',
            'caption_bgcolor' => 'rgba(34, 34, 34, 0.8)',
            'group_animation' => 'slide',
            'group_animation_direction' => 'horizontal',
            'group_animation_reverse' => 0,
            'group_animation_duration' => '600',
            'group_animation_easing' => 'swing',
            'group_autoslide' => 1,
            'group_autoloop' => 1,
            'group_pauseonaction' => 1,
            'group_pauseonhover' => 1,
            'group_slide_duration' => '7000',
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
                    'thumbnail_size',
                    'carousel_minitems',
                    'carousel_maxitems',
                    'carousel_move'
                ],
                'fieldNameFrom' => 'group_type',
                'refField' => implode(
                    ',', [
                    'carousel',
                    'basic-carousel',
                    ]
                ),
            ],
            [
                'fieldName' => [
                                'overlay_position',
                                'overlay_textcolor',
                                'overlay_bgcolor',
                                'overlay_hovercolor',
                                'nav_note',
                                'pagination_note'
                            ],
                'fieldNameFrom' => 'group_type',
                'refField' => 'overlay',
            ],
            [
                'fieldName' => ['custom_theme'],
                'fieldNameFrom' => 'theme',
                'refField' => 'custom',
            ],
            [
                'fieldName' => [
                    'nav_style',
                    'nav_size',
                    'nav_position',
                    'nav_color'
                ],
                'fieldNameFrom' => 'nav_show',
                'refField' => implode(
                    ',', [
                    'hover',
                    'always',
                    ]
                ),
            ],
            [
                'fieldName' => [
                                'nav_show',
                                'nav_style',
                                'nav_position',
                                'nav_color',
                                'nav_size',
                                'pagination_show',
                                'pagination_style',
                                'pagination_position',
                                'pagination_color',
                                'pagination_hover_color',
                                'pagination_active_color'
                            ],
                'fieldNameFrom' => 'group_type',
                'refField' => implode(
                    ',', [
                    'basic',
                    'carousel',
                    'basic-carousel',
                    ]
                ),
            ],
            [
                'fieldName' => [
                    'pagination_style',
                    'pagination_position',
                    'pagination_color',
                    'pagination_hover_color',
                    'pagination_active_color'
                ],
                'fieldNameFrom' => 'pagination_show',
                'refField' => implode(
                    ',', [
                    'hover',
                    'always',
                    ]
                ),
            ],
            [
                'fieldName' => [
                    'loader_position',
                    'loader_color',
                    'loader_bgcolor'
                ],
                'fieldNameFrom' => 'loader_show',
                'refField' => 1,
            ],
            [
                'fieldName' => [
                    'group_animation_direction',
                    'group_animation_easing'
                ],
                'fieldNameFrom' => 'group_animation',
                'refField' => 'slide',
            ],
            [
                'fieldName' => [
                    'group_autoloop',
                    'group_pauseonaction',
                    'group_pauseonhover'
                ],
                'fieldNameFrom' => 'group_autoslide',
                'refField' => 1,
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
        return __('Group Config');
    }

    /**
     * Prepare title for tab.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
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
