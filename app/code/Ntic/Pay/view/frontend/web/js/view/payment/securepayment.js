define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'securepayment',
                component: 'Ntic_Pay/js/view/payment/method-renderer/securepayment-method'
            }
        );
        return Component.extend({});
    }
);