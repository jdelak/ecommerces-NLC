define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Ntic_Pay/payment/securepayment'
            },
            getUrl: function(){
                return window.checkoutConfig.payment.securePayment.redirectUrl;
            },
            getMailingAddress: function () {
                return window.checkoutConfig.payment.checkmo.mailingAddress;
            },
        });
    }
);
