/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'jquery',
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        ko,
        $,
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'cb',
                component: 'Ntic_Payment/js/view/payment/method-renderer/cc-form'
            }
        );


        /** Add view logic here if needed */
        return Component.extend({});
    }
);

console.log('cb.js');