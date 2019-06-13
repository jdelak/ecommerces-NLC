require(['jquery', 'Magento_Ui/js/modal/modal'], function ($,modal) {
    // ################# SUBMIT ###################
    $('.cash-submit-payment').on('click', function() {
        $('.modali').fadeOut('slow');
    });

    $('#submit-form-cash').on('click', function () {
        if($('.cash-validate').is(":visible")) {
            $('#form-cash').submit();
        } else {
            alert('Merci de choisir un mode de paiement !');
        }
    });

    // Button enregistrer dans la modal
    $('.submit-payment').on('click', 'button', function() {
        // Différencie Multi de Cash
        if($(this).parents().hasClass('modal-cash')) {
            // CB
            if($('.submit-payment button').hasClass('ok-cb')) {
                // ADD INPUT HIDE
                $('#form-cash').append('<input type="hidden" name="payment_mode" value="cb" />');
                $('.cash-validate').show().css('display', 'flex').html('<div class="paiement-ok">CB <i class="fa fa-check" aria-hidden="true"></i></div>').addClass('flex-between').addClass('block-payment-ok');
                $('.no-validate-payment').hide();
                $('.multi-validate').hide();
            }

            // IBAN
            if($('.submit-payment button').hasClass('ok-iban')) {

                // ADD INPUT HIDE
                $('#form-cash').append('<input type="hidden" name="payment_mode" value="iban" />');
                $('.cash-validate').show().css('display', 'flex').html('<div class="paiement-ok">IBAN <i class="fa fa-check" aria-hidden="true"></i></div>').addClass('flex-between').addClass('block-payment-ok');
                $('.no-validate-payment').hide();
                $('.multi-validate').hide();
            }

            // CHECK
            if($('.submit-payment button').hasClass('ok-check')) {
                // ADD INPUT HIDE
                $('#form-cash').append('<input type="hidden" name="payment_mode" value="check" />');
                $('.cash-validate').show().css('display', 'flex').html('<div class="paiement-ok">CHECK <i class="fa fa-check" aria-hidden="true"></i></div>').addClass('flex-between').addClass('block-payment-ok');
                $('.no-validate-payment').hide();
                $('.multi-validate').hide();
            }
        }

        $('.modali').fadeOut('slow');
    });
});
