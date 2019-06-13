require(['jquery', 'Magento_Ui/js/modal/modal'], function ($,modal) {
    // ### Fonctionnalité metier ###
    var btn_cach  = $('#choose-payment button[data-choose="cash"]');
    var btn_cash_only = $('#choose-payment button[data-choose="cash-only"]');
    var btn_multi = $('#choose-payment button[data-choose="multi"]');
    var btn_hidden = $('.hidden-button');
    var mode_payment = $('.button-choose-payment button');

    // Defaut
    $('#payment-cash').hide();
    $('#payment-multi').hide();
    $('#payment-subscription').hide();

    // Affichage abo
    if(btn_hidden.attr('data-choose') == 'subscription') {
        $('#payment-cash').hide();
        $('#payment-multi').hide();
        $('#payment-subscription').show();

        // Différencie Cash de Multi et abo
        $('.modali').addClass('modal-subscription');
        $('.modali').removeClass('modal-multi');
        $('.modali').removeClass('modal-cash');

        // Modal => retire le check
        $('.modali_content .div-check').hide();
        $('.close,.close_modal').closest('.modali');
    }

    // Affichage cash si le montant est inférieur à 60€
    if(btn_hidden.attr('data-choose') == 'cash-only') {
        $('#payment-subscription').hide();
        $('#payment-multi').hide();
        $('#payment-cash').show();

        // Différencie Cash de Multi et abo
        $('.modali').removeClass('modal-subscription');
        $('.modali').removeClass('modal-multi');
        $('.modali').addClass('modal-cash');
    }

    // Detail
    $('.more').on('click',function(){
        $('#my-orders-table').toggle();
    });

    btn_cach.on('click',function(){
        $('#payment-cash').show();
        $('#payment-multi').hide();
        $('#payment-subscription').hide();

        // Différencie Cash de Multi et abo
        $('.modali').removeClass('modal-subscription');
        $('.modali').addClass('modal-cash');
        $('.modali').removeClass('modal-multi');

        // Modal => rajoute le check
        $('.modali_content .div-check').show();
        $('.close,.close_modal').closest('.modali');
    });

    btn_multi.on('click',function(){
        $('#payment-multi').show();
        $('#payment-cash').hide();
        $('#payment-subscription').hide();

        // Différencie Cash de Multi
        $('.modali').removeClass('modal-subscription');
        $('.modali').addClass('modal-multi');
        $('.modali').removeClass('modal-cash');

        // Modal => retire le check
        $('.modali_content .div-check').hide();
        $('.close,.close_modal').closest('.modali');
    });

    // Clique sur les buttons CB / CHECK / IBAN
    mode_payment.on('click',function (){
        var id = $(this).attr('id');
        var split_id = id.split('-');
        console.log(split_id);

        if(split_id[3] == 'iban'){
            // SUBMIT
            $('.submit-payment button').addClass('ok-iban');
            $('.submit-payment button').removeClass('ok-cb');
            $('.submit-payment button').removeClass('ok-check');
        } else if(split_id[3] == 'check'){
            // SUBMIT
            $('.submit-payment button').addClass('ok-check');
            $('.submit-payment button').removeClass('ok-cb');
            $('.submit-payment button').removeClass('ok-iban');
        } else if(split_id[3] == 'cb'){
            // SUBMIT
            $('.submit-payment button').addClass('ok-cb');
            $('.submit-payment button').removeClass('ok-iban');
            $('.submit-payment button').removeClass('ok-check');
        }
    });

    // MODAL CASH

    // CB / CHECK / IBAN
    $('.cash-button-payment').on('click',function(){
        var id = $(this).attr('id');
        var id_split = id.split('-');

        if($('.step_'+ id_split[3]).hasClass('modal-cash')) {
            $('.step_'+ id_split[3]).show();
        }
    });

    // MODAL MULTI

    // CB / IBAN
    $('.multi-button-payment').on('click',function(){
        var id = $(this).attr('id');
        var id_split = id.split('-');

        if($('.step_'+ id_split[3]).hasClass('modal-multi')) {
            $('.step_'+ id_split[3]).show();
        }
    });

    // MODAL SUBSCRIPTION

    // CB / IBAN
    $('.subscription-button-payment').on('click',function(){
        var id = $(this).attr('id');
        var id_split = id.split('-');

        if($('.step_'+ id_split[3]).hasClass('modal-subscription')) {
            $('.step_'+ id_split[3]).show();
        }
    });

    // Modal close
    $('.close,.close_modal').on('click',function(){
        $('.modali').fadeOut('slow');
    });
});
