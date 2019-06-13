define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Ntic_PayCustom/payment/paymentcustom'
            },
            getUrl: function(){
                return window.checkoutConfig.payment.customPayment.redirectUrl;
            },
            getMailingAddress: function () {
                return window.checkoutConfig.payment.checkmo.mailingAddress;
            },
        });
    }
);
