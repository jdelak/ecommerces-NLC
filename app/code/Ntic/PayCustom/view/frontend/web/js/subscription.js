require(['jquery', 'Magento_Ui/js/modal/modal'], function ($,modal) {
    /*-------------------------- FUNCTION ---------------------------*/

    // Compte les class active
    function countActiveClass(id) {
        // Coumpte les active-class
        var count_active_class = $('#' + id).find('.active-class').length;

        return count_active_class;
    }

    // Check des methodes de paiement
    function checkActiveClass() {
        var result = true;
        $('.active-class').each(function() {
            var id = $(this).attr('id');
            if(id.indexOf("subscription") > -1) {
                var loop_id = id.split('-');

                var check = $('.active-class .block-choose-payment-'+ loop_id[3] +'-'+ loop_id[4]).find('label').hasClass('choose-active');

                if(check == false) {
                    return result = false;
                }
            }
        });

        return result;
    }

    // Compte les inputs lock
    function countInputLock(id) {
        var count_input_block = $('#' + id).find('.lock-active').length;

        return count_input_block;
    }

    // Count parent
    function count_parent() {
        // Coumpte les active-class
        var count_parent = $('.div-subscription .block-parent-parcel').length;

        return count_parent;
    }

    // Somme des inputs pourcentage lock
    function sumInputPercentageLock(id) {
        var sum = 0;
        $('#' + id).find('.active-class .lock-active input').each(function() {
            sum = Number($(this).val()) + parseInt(sum);
        });

        return sum;
    }

    // Somme des inputs pourcentage
    function sumInputPercentage(id) {
        var sum = 0;
        $('#' + id).find('.active-class .choose-subscription-percentage input').each(function() {
            sum = Number($(this).val()) + parseInt(sum);
        });

        return sum;
    }

    // Cacule le nouveau montant
    function newAmount(total, percentage) {
        var result = parseFloat(total) * (parseFloat(percentage) / 100);

        return Math.round(result * 100) / 100;
    }
    
    // Gestion des dates
    $('.first-select-class').on('change', function() {
        var first_value = $(this).val();
        $('.select-date').each(function(index) {
            $(this).val(first_value);
            if(first_value > $(this).find('.last-day').val()) {
                $(this).find('.last-day').attr("selected", "selected");
            }
        });

        // Cherche si c'est le last-day
        if($("option:selected", this).attr("class") == 'last-day') {
            $('.select-date').each(function() {
                $('.last-day').attr("selected", "selected");
            });
        }

        $('.message-delivery span').html(first_value + $('.first-month-year').html());
    });

    // Gestion des first boutton payment
    $('.first-button-payment-class').on('click', function() {
        var id = $(this).attr('id');
        var clean_id = id.split('-');

        if(clean_id[0] == 'cb') {
            var message_day = '2 jours';

        } else if(clean_id[0] == 'check' || clean_id[0] == 'iban') {
            var message_day = '15 jours';

        }

        $('.message-delivery').html('Vous avez choisit le payment par '+ clean_id[0].toUpperCase() + ', votre colis sera livré avec un délais de '+ message_day);
    });

    // Gestion des inputs pourcentage
    $('.block-parent-parcel').each(function() {
        var id = $(this).attr('id');

        // Input
        $('#' + id).find('.active-class .choose-subscription-percentage input').on('change', function() {
            // Récup le grand total (prix)
            var max_parcel = $('#max-parcel').val();
            var big_total = $('input[name="total_price"]').val() * max_parcel;
            var min_percentage = 10;

            // Valeur pas plus petite que min_percentage
            if ($(this).val() < min_percentage) {
                $(this).val(min_percentage);
            }

            // Max valeur
            if (countInputLock(id) > 0) {
                var remove_sum_input_lock = 100 - sumInputPercentageLock(id);
                var input_not_block = countActiveClass(id) - countInputLock(id);
                var calcul_max_value = min_percentage * (input_not_block - 1);
                var max_value = remove_sum_input_lock - calcul_max_value;
            } else {
                var calcul_max_value = min_percentage * (countActiveClass(id) - 1);
                var max_value = 100 - calcul_max_value;
            }

            if ($(this).val() > max_value) {
                $(this).val(max_value);
            }

            if (input_not_block == 1) {
                $(this).val(max_value);
            }

            // Calcul pourcentage
            if (sumInputPercentage(id) != 100) {
                // Check le nombre input lock
                if (countInputLock(id) > 0) {
                    var sum_lock = 100 - sumInputPercentageLock(id);
                    var other_input = sum_lock - $(this).val();
                    var number_not_lock = (countActiveClass(id) - 1) - countInputLock(id);
                    var percentage = other_input / number_not_lock;
                } else {
                    // Calcule des autres inputs
                    var other_input = 100 - $(this).val();
                    var percentage = other_input / (countActiveClass(id) - 1);
                }

                // Change montant
                var parent = $(this).parent().parent();
                // Block price
                var div_block_price = parent.find('.block-subscription-price');
                var id_block_price = div_block_price.attr('id');
                // Hidden amount
                var div_hidden_amount = parent.find('.hidden-amount');
                var id_hidden_amount = div_hidden_amount.attr('id');
                // New amount
                var new_amount = newAmount(big_total, $(this).val());
                var str_amount = "" + new_amount;
                var str_new_amount = str_amount.replace(".", ",");

                $('#' + id_block_price).html(str_new_amount + ' <span>€</span>');
                $('#' + id_hidden_amount + ' input').val(new_amount);

                // Change les autres input avec le nouveau pourcentage
                $('#' + id).find('.active-class .choose-subscription-percentage input').not(this).each(function () {
                    // Change value des autre inputs
                    if (!$(this).attr('readonly')) {
                        $(this).val(Math.round(percentage * 100) / 100);
                    }

                    // Change montant
                    var parent = $(this).parent().parent();
                    // Block price
                    var div_block_price = parent.find('.block-subscription-price');
                    var id_block_price = div_block_price.attr('id');
                    // Hidden amount
                    var div_hidden_amount = parent.find('.hidden-amount');
                    var id_hidden_amount = div_hidden_amount.attr('id');
                    // New amount
                    var new_amount = newAmount(big_total, $(this).val());
                    var str_amount = "" + new_amount;
                    var str_new_amount = str_amount.replace(".", ",");

                    $('#' + id_block_price).html(str_new_amount + ' <span>€</span>');
                    $('#' + id_hidden_amount + ' input').val(new_amount);
                });
            }
        });
    });

    // Click button-remove-payment (-)
    $('.button-remove-payment').on('click', function(e) {
        e.preventDefault();
        var max_parcel = $('#max-parcel').val();
        var big_total = $('input[name="total_price"]').val() * max_parcel;

        var parent = $(this).parent();
        var id = parent.attr('id');
        var loop_id = id.split('-');
        var this_block = '#form-subscription-payment-'+ loop_id[1] +'-'+ loop_id[2];

        $(this_block).removeClass('active-class').addClass('hidden-class');

        // Ajoute false sur le champ caché input-hidden-active
        $('.input-hidden-active-'+ loop_id[1] +'-'+ loop_id[2] +' input').val('false');

        $('#parent-parcel-'+ loop_id[1]).find('.active-class .choose-subscription-percentage input').each(function() {
            var percentage = 100 / countActiveClass('parent-parcel-'+ loop_id[1]);
            $(this).val(Math.round(percentage * 100) / 100);

            // Amount
            var price = $('#parent-parcel-'+ loop_id[1]).find('.block-subscription-price').html();
            // New amount
            var new_amount = newAmount(big_total, $(this).val());
            var str_amount = "" + new_amount;
            var str_new_amount = str_amount.replace(".", ",");
            
            // Block price
            $('#parent-parcel-'+ loop_id[1]).find('.block-subscription-price').each(function() {
                $(this).html(str_new_amount + ' <span>€</span>');
            });

            // Hidden amount
            $('#parent-parcel-'+ loop_id[1]).find('.hidden-amount input').each(function() {
                $(this).val(new_amount);
            });

            // Gestion du boutton (+)
            var max_parcel = $('#max-parcel').val();

            if (countActiveClass('parent-parcel-'+ loop_id[1]) < max_parcel) {
                $('#button-add-' + loop_id[1] + '-' + (loop_id[2] - 1)).addClass('add-active');
            }
        });
    });

    // Click button-add-payment (+)
    $('.button-add-subscription-payment').on('click', function(e) {
        e.preventDefault();

        var parent = $(this).parent();
        var id = parent.attr('id');
        var loop_id = id.split('-');
        var this_block = '#form-subscription-payment-'+ loop_id[2] +'-'+ loop_id[3];
        var next_block = '#form-subscription-payment-'+ loop_id[2] +'-'+ (Number(loop_id[3]) + 1);

        // Cache une fois cliquer
        $(this).parent().removeClass('add-active');

        // Récup le grand total (prix)
        var max_parcel = $('#max-parcel').val();
        var big_total = $('input[name="total_price"]').val() * max_parcel;

        $(next_block).removeClass('hidden-class').addClass('active-class');

        // Ajoute true sur le champ caché input-hidden-active
        $('.active-class .input-hidden-active input').val('true');

        // Divise le big_total pour le nombre de fraction
        var result = (big_total / countActiveClass());

        $('#parent-parcel-'+ loop_id[2]).find('.active-class .choose-subscription-percentage input').each(function() {
            var percentage = 100 / countActiveClass('parent-parcel-'+ loop_id[2]);
            $(this).val(Math.round(percentage * 100) / 100);

            // Amount
            var price = $('#parent-parcel-'+ loop_id[2]).find('.block-subscription-price').html();
            // New amount
            var new_amount = newAmount(big_total, $(this).val());
            var str_amount = "" + new_amount;
            var str_new_amount = str_amount.replace(".", ",");

            // Block price
            $('#parent-parcel-'+ loop_id[2]).find('.block-subscription-price').each(function() {
                $(this).html(str_new_amount + ' <span>€</span>');
            });

            // Hidden amount
            $('#parent-parcel-'+ loop_id[2]).find('.hidden-amount input').each(function() {
                $(this).val(new_amount);
            });
        });
    });

    // Click button save form check
    $('.btn-close-form-check').on('click', function(e) {
        e.preventDefault();

        var parent = $(this).parent();
        // id parent
        var id = parent.attr('id');
        var label_id = id.split('-');

        // This id
        var this_id = $(this).attr('id');
        var this_id_split = this_id.split('-');

        console.log($('.number-check-0-0').val());

        // Numéro du chèque (number of check)

        // Proprietaire du chèque (owner of check)

        // Nom de la banque (Name of your bank)

        // Cache le form
        $('#' + id).hide();
    });

    // Button enregistrer dans la modal
    $('.submit-payment').on('click', 'button', function() {
        // Différencie Multi de Cash
        if($(this).parents().hasClass('modal-subscription')) {
            // Check input vide
            console.log($(this).parents());

            // CB
            if ($('.submit-payment button').hasClass('ok-cb')) {
                // ADD INPUT HIDE
                $('#form-subscription').append('<input type="hidden" name="payment_mode" value="cb" />');
                $('.subscription-validate').show().css('display', 'flex').append('<div class="paiement-ok">CB <i class="fa fa-check" aria-hidden="true"></i></div>').addClass('flex-between').addClass('block-payment-ok');
                $('.no-validate-payment').hide();
            }

            // IBAN
            if ($('.submit-payment button').hasClass('ok-iban')) {
                // ADD INPUT HIDE
                $('#form-subscription').append('<input type="hidden" name="payment_mode" value="iban" />');
                $('.subscription-validate').show().css('display', 'flex').append('<div class="paiement-ok">IBAN <i class="fa fa-check" aria-hidden="true"></i></div>').addClass('flex-between').addClass('block-payment-ok');
                $('.no-validate-payment').hide();
            }
        }

        $('.modali').fadeOut('slow');
    });

    // Envoie du form via le boutton Pay
    $('#submit-form-subscription').on('click', function () {
        if(checkActiveClass() == true) {
            $('#form-subscription').submit();
        } else {
            alert('Merci de choisir un mode de paiement pour TOUTE les échéances !');
        }
    });
});