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

namespace SolideWebservices\Flexslider\Helper;

/**
 * Flexslider Options Helper
 */
class Options extends \SolideWebservices\Flexslider\Helper\Data
{
    /**
     * Make option array.
     *
     * @param array  $values         Options array.
     * @param bool   $includeEmpty   Include an empty option.
     * @param string $emptyTextValue Value for empty option.
     * @param string $emptyText      Text for empty option.
     *
     * @return $options[]
     */
    public function toOptionArray(
        $values,
        $includeEmpty = false,
        $emptyTextValue = '',
        $emptyText = '-- Please Select --'
    ) {
        $options = [];

        if ($includeEmpty) {
            $options[] = [
                'value' => $emptyTextValue,
                'label' => __($emptyText),
            ];
        }

        foreach ($values as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => __($label),
            ];
        }

        return $options;
    }

    /**
     * Yes or No options.
     *
     * @return $values[]
     */
    public function getYesNoArray()
    {
        $values = [
            '1' => __('Yes'),
            '0' => __('No'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Group position options.
     *
     * @return $values[]
     */
    public function getGroupPositionsArray()
    {
        /* let's see what positions are enabled */
        $selectedEnabled = $this->getEnabledPositions('selected');
        $globalEnabled = $this->getEnabledPositions('global');
        $accountEnabled = $this->getEnabledPositions('customer');
        $checkoutEnabled = $this->getEnabledPositions('checkout');

        return [
            [
                'label' => __('-- Please Select --'),
                'value' => ''
            ],
            [
                'label' => __('Position On Selected CMS Pages,
                    Categories or Products'),
                'value' => [
                    [
                        'value' => 'content_top',
                        'label' => __('Main Content Top'),
                        'disabled' => $selectedEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'content_bottom',
                        'label' => __('Main Content Bottom'),
                        'disabled' => $selectedEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'right_top',
                        'label' => __('Right Sidebar Top'),
                        'disabled' => $selectedEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'right_bottom',
                        'label' => __('Right Sidebar Bottom'),
                        'disabled' => $selectedEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'left_top',
                        'label' => __('Left Sidebar Top'),
                        'disabled' => $selectedEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'left_bottom',
                        'label' => __('Left Sidebar Bottom'),
                        'disabled' => $selectedEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'footer_top',
                        'label' => __('Footer Top'),
                        'disabled' => $selectedEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'footer_bottom',
                        'label' => __('Footer Bottom'),
                        'disabled' => $selectedEnabled == true ? '' : 'disabled'
                    ]
                ]
            ],
            [
                'label' => '---------',
                'value' => [
                    [
                        'value' => '-',
                        'label' => __('The Following Positions Will
                            Discard Any CMS,'),
                        'disabled' => 'disabled'
                    ],
                    [
                        'value' => '--',
                        'label' => __('Category And Product Selections In
                            Group Settings'),
                        'disabled' => 'disabled'
                    ],
                    [
                        'value' => '---',
                        'label' => __('These Positions Are Disabled By Default
                            For Optimal Performance'),
                        'disabled' => 'disabled'],
                    [
                        'value' => '----',
                        'label' => __('Enable Them In The General Settings
                            If Desired'),
                        'disabled' => 'disabled'
                    ],
                    [
                        'value' => '-----',
                        'label' => '------',
                        'disabled' => 'disabled'
                    ]
                ]
            ],
            [
                'label' => __('Custom (select this when using template
                    tag or XML)'),
                'value' => [
                    ['value' => 'CUSTOM', 'label' => __('Custom')]
                ]
            ],
            [
                'label' => __('Show On All Pages (Global)'),
                'value' => [
                    [
                        'value' => 'all_content_top',
                        'label' => __('All Main Content Top'),
                        'disabled' => $globalEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'all_content_bottom',
                        'label' => __('All Main Content Bottom'),
                        'disabled' => $globalEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'all_right_top',
                        'label' => __('All Right Sidebar Top'),
                        'disabled' => $globalEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'all_right_bottom',
                        'label' => __('All Right Sidebar Bottom'),
                        'disabled' => $globalEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'all_left_top',
                        'label' => __('All Left Sidebar Top'),
                        'disabled' => $globalEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'all_left_bottom',
                        'label' => __('All Left Sidebar Bottom'),
                        'disabled' => $globalEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'all_footer_top',
                        'label' => __('All Footer Top'),
                        'disabled' => $globalEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'all_footer_bottom',
                        'label' => __('All Footer Bottom'),
                        'disabled' => $globalEnabled == true ? '' : 'disabled'
                    ]
                ]
            ],
            [
                'label' => __('Customer Based Positions'),
                'value' => [
                    [
                        'value' => 'account_login_top',
                        'label' => __('Account Login Top'),
                        'disabled' => $accountEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'account_login_bottom',
                        'label' => __('Account Login Bottom'),
                        'disabled' => $accountEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'account_top',
                        'label' => __('Account Dashboard Top'),
                        'disabled' => $accountEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'account_bottom',
                        'label' => __('Account Dashboard Bottom'),
                        'disabled' => $accountEnabled == true ? '' : 'disabled'
                    ]
                ]
            ],
            [
                'label' => __('Checkout Based Positions'),
                'value' => [
                    [
                        'value' => 'checkout_cart_top',
                        'label' => __('Cart Top'),
                        'disabled' => $checkoutEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'checkout_cart_bottom',
                        'label' => __('Cart Bottom'),
                        'disabled' => $checkoutEnabled == true ? '' : 'disabled'
                    ],
                    [
                        'value' => 'checkout_page_top',
                        'label' => __('Checkout Page Top'),
                        'disabled' => $checkoutEnabled == true ? '' : 'disabled'],
                    [
                        'value' => 'checkout_cart_bottom',
                        'label' => __('Checkout Page Bottom'),
                        'disabled' => $checkoutEnabled == true ? '' : 'disabled'
                    ]
                ]
            ],
        ];
    }

    /**
     * Group type options.
     *
     * @return $values[]
     */
    public function getGroupTypeArray()
    {
        $values = [
            'basic'          => __('Basic slider'),
            'carousel'       => __('Carousel'),
            'basic-carousel' => __('Basic slider with carousel navigation'),
            'overlay'        => __('Basic slider with overlay navigation'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Overlay position options.
     *
     * @return $values[]
     */
    public function getOverlayPositionArray()
    {
        $values = [
            'right' => __('Right'),
            'left'  => __('Left'),
        ];

        return $this->toOptionArray(
            $values,
            $includeEmpty = true,
            $emptyTextValue = 'right'
        );
    }

    /**
     * Theme options.
     *
     * @return $values[]
     */
    public function getThemeArray()
    {
        $values = [
            'default'   => __('Default'),
            'woothemes' => __('WooThemes'),
            'blank'     => __('Blank'),
            'custom'    => __('Custom (see manual to create custom theme)'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Navigation and pagination show options.
     *
     * @return $values[]
     */
    public function getNavShowPaginationShowArray()
    {
        $values = [
            'hover'  => __('On hover'),
            'always' => __('Always'),
            'no'     => __('Never'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Nav style options.
     *
     * @return $values[]
     */
    public function getNavStyleArray()
    {
        $values = [
            'type-1'  => __('Arrow Type'). ' 1',
            'type-2'  => __('Arrow Type'). ' 2',
            'type-3'  => __('Arrow Type'). ' 3',
            'type-4'  => __('Arrow Type'). ' 4',
            'type-5'  => __('Arrow Type'). ' 5',
            'type-6'  => __('Arrow Type'). ' 6',
            'type-7'  => __('Arrow Type'). ' 7',
            'type-8'  => __('Arrow Type'). ' 8',
            'type-9'  => __('Arrow Type'). ' 9',
            'type-10' => __('Arrow Type'). ' 10',
            'type-11' => __('Arrow Type'). ' 11',
            'type-12' => __('Arrow Type'). ' 12',
            'type-13' => __('Arrow Type'). ' 13',
            'type-14' => __('Arrow Type'). ' 14',
            'type-15' => __('Arrow Type'). ' 15',
            'type-16' => __('Arrow Type'). ' 16',
            'type-17' => __('Arrow Type'). ' 17',
            'type-18' => __('Arrow Type'). ' 18',
            'type-19' => __('Arrow Type'). ' 19',
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Example nav styles.
     *
     * @return string
     */
    public function getExampleNavStyles()
    {
        $html = '<div class="flexslider-tooltip">'. __('Show Examples') .'
            <div class="tooltiptext">
                <div class="arrow-example-row arrow-type-1">
                    <div class="arrow-label">'. __('Arrow Type') .' 1</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-2">
                    <div class="arrow-label">'. __('Arrow Type') .' 2</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-3">
                    <div class="arrow-label">'. __('Arrow Type') .' 3</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-4">
                    <div class="arrow-label">'. __('Arrow Type') .' 4</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-5">
                    <div class="arrow-label">'. __('Arrow Type') .' 5</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-6">
                    <div class="arrow-label">'. __('Arrow Type') .' 6</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-7">
                    <div class="arrow-label">'. __('Arrow Type') .' 7</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-8">
                    <div class="arrow-label">'. __('Arrow Type') .' 8</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-9">
                    <div class="arrow-label">'. __('Arrow Type') .' 9</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-10">
                    <div class="arrow-label">'. __('Arrow Type') .' 10</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-11">
                    <div class="arrow-label">'. __('Arrow Type') .' 11</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-12">
                    <div class="arrow-label">'. __('Arrow Type') .' 12</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-13">
                    <div class="arrow-label">'. __('Arrow Type') .' 13</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-14">
                    <div class="arrow-label">'. __('Arrow Type') .' 14</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-15">
                    <div class="arrow-label">'. __('Arrow Type') .' 15</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-16">
                    <div class="arrow-label">'. __('Arrow Type') .' 16</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-17">
                    <div class="arrow-label">'. __('Arrow Type') .' 17</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-18">
                    <div class="arrow-label">'. __('Arrow Type') .' 18</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
                <div class="arrow-example-row arrow-type-19">
                    <div class="arrow-label">'. __('Arrow Type') .' 19</div>
                    <div class="arrow-example-previous">previous</div>
                    <div class="arrow-example-next">next</div>
                </div>
            </div>
        </div>';

        return $html;
    }

    /**
     * Nav size options.
     *
     * @return $values[]
     */
    public function getNavSizeArray()
    {
        $values = [
            'normal'  => __('Normal'),
            'bigger'  => __('Bigger'),
            'biggest' => __('Biggest'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Nav position options.
     *
     * @return $values[]
     */
    public function getNavPositionArray()
    {
        $values = [
            'inside'       => __('Inside slider on both sides'),
            'outside'      => __('Outside the slider on both sides'),
            'inside-left'  => __('Inside slider grouped left'),
            'inside-right' => __('Inside slider grouped right'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Pagination style options.
     *
     * @return $values[]
     */
    public function getPaginationStyleArray()
    {
        $values = [
            'circular'     => __('Circular'),
            'squared'      => __('Square'),
            'circular-bar' => __('Circular with bar'),
            'square-bar'   => __('Square with bar'),
            'dots'         => __('Simple dots'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Pagination position options.
     *
     * @return $values[]
     */
    public function getPaginationPositionArray()
    {
        $values = [
            'below'         => __('Below the slider'),
            'above'         => __('Above the slider'),
            'inside-bottom' => __('Inside bottom slider'),
            'inside-top'    => __('Inside top slider'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Loader position options.
     *
     * @return $values[]
     */
    public function getLoaderPositionArray()
    {
        $values = [
            'top'    => __('Top'),
            'bottom' => __('Bottom'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Group animation options.
     *
     * @return $values[]
     */
    public function getGroupAnimationArray()
    {
        $values = [
            'slide' => __('Slide'),
            'fade'  => __('Fade'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Group animation direction options.
     *
     * @return $values[]
     */
    public function getGroupAnimationDirectionArray()
    {
        $values = [
            'horizontal' => __('Horizontal'),
            'vertical'   => __('Vertical'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Group animation easing options.
     *
     * @return $values[]
     */
    public function getGroupAnimationEasingArray()
    {
        $values = [
            'jswing'           => 'Swing',
            'easeInQuad'       => 'easeInQuad',
            'easeOutQuad'      => 'easeOutQuad',
            'easeInOutQuad'    => 'easeInOutQuad',
            'easeInCubic'      => 'easeInCubic',
            'easeOutCubic'     => 'easeOutCubic',
            'easeInOutCubic'   => 'easeInOutCubic',
            'easeInQuart'      => 'easeInQuart',
            'easeOutQuart'     => 'easeOutQuart',
            'easeInOutQuart'   => 'easeInOutQuart',
            'easeInQuint'      => 'easeInQuint',
            'easeOutQuint'     => 'easeOutQuint',
            'easeInOutQuint'   => 'easeInOutQuint',
            'easeInSine'       => 'easeInSine',
            'easeOutSine'      => 'easeOutSine',
            'easeInOutSine'    => 'easeInOutSine',
            'easeInExpo'       => 'easeInExpo',
            'easeOutExpo'      => 'easeOutExpo',
            'easeInOutExpo'    => 'easeInOutExpo',
            'easeInCirc'       => 'easeInCirc',
            'easeOutCirc'      => 'easeOutCirc',
            'easeInOutCirc'    => 'easeInOutCirc',
            'easeInElastic'    => 'easeInElastic',
            'easeOutElastic'   => 'easeOutElastic',
            'easeInOutElastic' => 'easeInOutElastic',
            'easeInBack'       => 'easeInBack',
            'easeOutBack'      => 'easeOutBack',
            'easeInOutBack'    => 'easeInOutBack',
            'easeInBounce'     => 'easeInBounce',
            'easeOutBounce'    => 'easeOutBounce',
            'easeInOutBounce'  => 'easeInOutBounce',
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Slide type options.
     *
     * @return $values[]
     */
    public function getSlideTypeArray()
    {
        $values = [
            'image'   => __('Image'),
            'youtube' => __('YouTube Video'),
            'vimeo'   => __('Vimeo Video'),
        ];

        return $this->toOptionArray(
            $values,
            $includeEmpty = true,
            $emptyTextValue = 'image'
        );
    }

    /**
     * Url target options.
     *
     * @return $values[]
     */
    public function getUrlTargetArray()
    {
        $values = [
            '_self'  => __('Same Window / Tab'),
            '_blank' => __('New Window / Tab'),
        ];

        return $this->toOptionArray($values);
    }

    /**
     * Caption position options.
     *
     * @return $values[]
     */
    public function getCaptionPositionsArray()
    {
        $values = [
            'random'       => __('Random Select'),
            'top'          => __('Top'),
            'bottom'       => __('Bottom'),
            'top-left'     => __('Top Left'),
            'top-right'    => __('Top Right'),
            'bottom-left'  => __('Bottom Left'),
            'bottom-right' => __('Bottom Right')
        ];

        return $this->toOptionArray(
            $values,
            $includeEmpty = true,
            $emptyTextValue = 'bottom-right'
        );
    }

}
