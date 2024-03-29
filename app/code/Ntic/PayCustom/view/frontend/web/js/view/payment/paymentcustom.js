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
                type: 'paymentcustom',
                component: 'Ntic_PayCustom/js/view/payment/method-renderer/paymentcustom-method'
            }
        );
        return Component.extend({});
    }
);