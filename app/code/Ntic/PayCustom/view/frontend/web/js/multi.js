require(['jquery', 'Magento_Ui/js/modal/modal'], function ($,modal) {
    // DROIT BUTTON
    $('.rank-button button').on('click', function() {
        $(this).toggleClass("active");

        if($(this).hasClass('active')) {
            $(this).html('DROITS DÉSACTIVÉ');
            $(this).removeClass('btn-warning').addClass('btn-danger');

            // Button lock
            $('.choose-percentage .lock-input').each(function() {
                $(this).hide();
            });

            // Input
            $('.choose-percentage input').each(function() {
                $(this).prop('disabled', true);
            });

            // Input date
            $('.block-table-data .date').each(function() {
                var parent = $(this).parent().parent();
                if(parent.attr('id') != 'form-payment_0') {
                    var val_input = $(this).find('input').val();
                    $(this).append('<div class="no-input">'+ val_input +'</div>');
                    $(this).find('input').hide();
                }
            });
        } else {
            $(this).html('DROITS ACTIVÉ');
            $(this).removeClass('btn-warning').removeClass('btn-danger').addClass('btn-success');

            // Button lock
            $('.choose-percentage .lock-input').each(function() {
                $(this).show();
            });

            // Input
            $('.choose-percentage input').each(function() {
                $(this).prop('disabled', false);
            });

            // Input date
            $('.block-table-data .date').each(function() {
                $(this).find('input').show();
                $(this).find('.no-input').remove();
            });
        }
    });

    // Add days
    function addDays(day, month, year, numberAddDays) {
        var date = new Date(year, month, day);
        var futurDate = date.setDate(date.getDate() + numberAddDays);
        return new Date(futurDate);
    }

    // Convert date us into date fr
    function convertDate(date) {
        var date    = new Date(date),
            year    = date.getFullYear(),
            month   = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth(),
            day     = date.getDate()  < 10 ? '0' + date.getDate()  : date.getDate(),
            newDate = day + '/' + month + '/' + year;

        return newDate;
    }

    // Compte les class active
    function countActiveClass() {
        // Coumpte les active-class
        var count_active_class = $('.active-class').length;

        return count_active_class;
    }

    // Compte les inputs lock
    function countInputLock() {
        var count_input_block = $('.lock-active').length;

        return count_input_block;
    }

    // Somme des inputs pourcentage
    function sumInputPercentage() {
        var sum = 0;
        $('.active-class .choose-percentage input').each(function() {
            sum = Number($(this).val()) + parseInt(sum);
        });

        return sum;
    }

    // Somme des inputs pourcentage lock
    function sumInputPercentageLock() {
        var sum = 0;
        $('.active-class .lock-active input').each(function() {
            sum = Number($(this).val()) + parseInt(sum);
        });

        return sum;
    }

    // Cacule le nouveau montant
    function newAmount(total, percentage) {
        var result = parseFloat(total) * (parseFloat(percentage) / 100);
        return Math.round(result * 100) / 100;
    }

    // Vérif du form CB
    function checkCB(input){
        var check = true;
        var reg = new RegExp("[0-9]","g");

        if(input.val().length < 15 || input.val().length > 16){
            check = false;
        }

        if(!reg.test(input.val())){
            check = false;
        }

        if(input.val().length == 16){
            if(!LuhnCheck(input.val())){
                check = false;
            }
        }

        if(check){
            champOk(input);
        } else{
            champErreur(input);
        }

        return check;
    }

    // Click button cb / check / iban
    $('.button-choose-payment label').on('click',function (){
        var id = $(this).attr('id');
        var clean_id = id.split('-');
        var form_id = 'form-' + id;

        // Ajoute la valeur dans le input caché
        var input = $(this).parent().find('.input-hidden input').val(clean_id[0]);
        // Gestion de la shipping-date
        if($('#payment-subscription').is(':visible')) {
            if($(this).parent().hasClass('parent-class')) {
                // Récupération des dates
                var parent = $(this).parent();
                var date_id = parent.parent().find('.date-subscription').attr('id');
                var select_day = $('#' + date_id).find('.select-date').val();
                var month_year = $('#' + date_id).find('input').val();
                var split_date = month_year.split('-');

                if(clean_id[0] == 'cb') {
                    $('#button-choose-payment-' + clean_id[1] + '-' + clean_id[2]).find('.input-hidden-shipping-date input').val(convertDate(addDays(select_day, split_date[0], split_date[1], 2)));
                } else if(clean_id[0] == 'check' || clean_id[0] == 'iban') {
                    $('#button-choose-payment-' + clean_id[1] + '-' + clean_id[2]).find('.input-hidden-shipping-date input').val(convertDate(addDays(select_day, split_date[0], split_date[1], 15)));
                }
            }
        }

        // Ajoute class sur le label
        $('#' + id).toggleClass('choose-active').siblings().removeClass('choose-active');
        // Affiche form check
        if(clean_id[0] == 'check') {
            $('#form-' + id).fadeToggle('slow');
        } else {
            if($('#payment-subscription').is(':visible')) {
                $('#form-check-' + clean_id[1] + '-' + clean_id[2]).hide();
            } else {
                $('#form-check-' + clean_id[1]).hide();
            }
        }
    });

    // Click button save form check
    $('.btn-close-form-check').on('click', function(e) {
        e.preventDefault();

        var parent = $(this).parent();
        var id = parent.attr('id');
        var label_id = id.split('-');

        // Cache le form
        $('#' + id).hide();
    });

    // Click button-add-payment (+)
    $('.button-add-payment').on('click', function(e) {
        e.preventDefault();

        // Récup le grand total (prix)
        var big_total = $('input[name="total_price"]').val();

        var first_hidden_class = $('.block-parcel').find('.hidden-class').attr('id');
        $('#'+ first_hidden_class).removeClass('hidden-class').addClass('active-class');

        // Ajoute true sur le champ caché input-hidden-active
        $('.active-class .input-hidden-active input').val('true');

        // Divise le big_total pour le nombre de fraction
        var result = (big_total / countActiveClass());

        $('.block-parcel').each(function() {
            var round_result = Math.round(result * 100) / 100;
            var str_amount = "" + round_result;
            var str_new_amount = str_amount.replace(".", ",");

            $('.block-price').html(str_new_amount + ' <span>€</span>');
            var percentage = 100 / countActiveClass();
            $('.choose-percentage input').val(Math.round(percentage * 100) / 100);

            // Amount
            var price = $(this).find('.block-price').html();
            if(price !== 'undefined') {
                var split_price = price.split("<span>");
                $('.hidden-amount input').val(parseInt(split_price[0]));
            }

            // Supprime class, changement de l'icon et supprime readonly de block-active
            var div_choose_percentage = $(this).find('.choose-percentage');
            div_choose_percentage.removeClass('lock-active');
            div_choose_percentage.find('input').attr("readonly", false);
            div_choose_percentage.find('.lock-input').removeClass('button-lock-active').html('<i class="fa fa-unlock" aria-hidden="true"></i>');
        });
    });

    // Click button-remove-payment (-)
    $('.multi-button-remove-payment').on('click', function(e) {
        e.preventDefault();

        // Récup le grand total (prix)
        var big_total = $('input[name="total_price"]').val();

        if(countActiveClass() > 2) {
            var first_hidden_class = $('.block-parcel').find('.active-class:last').attr('id');
            $('#' + first_hidden_class).removeClass('active-class').addClass('hidden-class');

            // Ajoute true sur le champ caché input-hidden-active
            $('.active-class:last .input-hidden-active input').val('false');

            // Divise le big_total pour le nombre de fraction
            var result = (big_total / countActiveClass());

            $('.block-parcel').each(function () {
                var round_result = Math.round(result * 100) / 100;
                var str_amount = "" + round_result;
                var str_new_amount = str_amount.replace(".", ",");

                $('.block-price').html(str_new_amount + ' <span>€</span>');
                var percentage = 100 / countActiveClass();
                $('.choose-percentage input').val(Math.round(percentage * 100) / 100);

                // Amount
                var price = $(this).find('.block-price').html();
                if (price !== 'undefined') {
                    var split_price = price.split("<span>");
                    $('.hidden-amount input').val(parseInt(split_price[0]));
                }

                // Supprime class, changement de l'icon et supprime readonly de block-active
                var div_choose_percentage = $(this).find('.choose-percentage');
                div_choose_percentage.removeClass('lock-active');
                div_choose_percentage.find('input').attr("readonly", false);
                div_choose_percentage.find('.lock-input').removeClass('button-lock-active').html('<i class="fa fa-unlock" aria-hidden="true"></i>');
            });
        }
    });

    // Gestion des inputs pourcentage
    $('.choose-percentage input').on('change',function() {
        // Récup le grand total (prix)
        var big_total = $('input[name="total_price"]').val();
        var min_percentage = 10;

        // Valeur pas plus petite que min_percentage
        if($(this).val() < min_percentage) {
            $(this).val(min_percentage);
        }

        // Max valeur
        if(countInputLock() > 0) {
            var remove_sum_input_lock = 100 - sumInputPercentageLock();
            var input_not_block = countActiveClass() - countInputLock();
            var calcul_max_value = min_percentage * (input_not_block - 1);
            var max_value = remove_sum_input_lock - calcul_max_value;
        } else {
            var calcul_max_value = min_percentage * (countActiveClass() - 1);
            var max_value = 100 - calcul_max_value;
        }

        if ($(this).val() > max_value) {
            $(this).val(max_value);
        }

        if(input_not_block == 1) {
            $(this).val(max_value);
        }

        // Calcul pourcentage
        if(sumInputPercentage() != 100) {
            // Check le nombre input lock
            if(countInputLock() > 0) {
                var sum_lock = 100 - sumInputPercentageLock();
                var other_input = sum_lock - $(this).val();
                var number_not_lock = (countActiveClass() - 1) - countInputLock();
                var percentage = other_input / number_not_lock;
            } else {
                // Calcule des autres inputs
                var other_input = 100 - $(this).val();
                var percentage = other_input / (countActiveClass() - 1);
            }

            // Change montant
            var parent = $(this).parent().parent();
            // Block price
            var div_block_price = parent.find('.block-price');
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
            $('.active-class .choose-percentage input').not(this).each(function () {
                // Change value des autre inputs
                if(!$(this).attr('readonly')) {
                    $(this).val(Math.round(percentage * 100) / 100);
                }

                // Change montant
                var parent = $(this).parent().parent();
                // Block price
                var div_block_price = parent.find('.block-price');
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

    // Gestion des input caché
    $('.hidden-class').each(function() {
        // $(this).find('input').val('');
    });

    // Click button lock pourcentage
    $('.lock-input').on('click', function() {
        // Récup le grand total (prix)
        var big_total = $('input[name="total_price"]').val();

        if($(this).hasClass('button-lock-active')) {
            // Supprime attr readonly
            var find_parent = $(this).parent();
            find_parent.find('input').attr("readonly", false);

            // Supprime class et changement de l'icon
            $(this).removeClass('button-lock-active');
            $(find_parent).removeClass('lock-active');
            $(this).html('<i class="fa fa-unlock" aria-hidden="true"></i>');
        } else {
            // Ajout attr readonly
            var find_parent = $(this).parent();
            find_parent.find('input').attr("readonly", true);
            // Cherche le nom de l'input
            var get_input_name = find_parent.find('input').attr('name');

            // Ajout class et changement de l'icon
            $(this).addClass('button-lock-active');
            $(find_parent).addClass('lock-active');
            $(this).html('<i class="fa fa-lock" aria-hidden="true"></i>');
        }

        // Somme des inputs lock
        // Change les autres input avec le nouveau pourcentage
        $('.choose-percentage input').each(function () {
            // Donne le nombre d'input pas lock
            var input_not_block = countActiveClass() - countInputLock();
            // Calcule la som lock - 100
            var total_not_lock = 100 - sumInputPercentageLock();
            var result_percentage = total_not_lock / input_not_block;

            if(!$(this).attr('readonly')) {
                $(this).val(Math.round(result_percentage * 100) / 100);
            }

            // Change montant
            var parent = $(this).parent().parent();
            // Block price
            var div_block_price = parent.find('.block-price');
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
    });

    // Button enregistrer dans la modal
    $('.submit-payment').on('click', 'button', function() {
        // Différencie Multi de Cash
        if($(this).parents().hasClass('modal-multi')) {
            // Check input vide
            //console.log($(this).parents());

            // CB
            if ($('.submit-payment button').hasClass('ok-cb')) {
                // ADD INPUT HIDE
                $('#form-multi').append('<input type="hidden" name="payment_mode" value="cb" />');
                $('.multi-validate').show().css('display', 'flex').append('<div class="paiement-ok">CB <i class="fa fa-check" aria-hidden="true"></i></div>').addClass('flex-between').addClass('block-payment-ok');
                $('.no-validate-payment').hide();
            }

            // IBAN
            if ($('.submit-payment button').hasClass('ok-iban')) {
                // ADD INPUT HIDE
                $('#form-multi').append('<input type="hidden" name="payment_mode" value="iban" />');
                $('.multi-validate').show().css('display', 'flex').append('<div class="paiement-ok">IBAN <i class="fa fa-check" aria-hidden="true"></i></div>').addClass('flex-between').addClass('block-payment-ok');
                $('.no-validate-payment').hide();
            }
        }

        $('.modali').fadeOut('slow');
    });

    // Envoie du form via le boutton Pay
    $('#submit-form-multi').on('click',function () {
        for (var i = 0; i < countActiveClass(); i++) {
            var check = $('.active-class .block-choose-payment-'+ i).find('label').hasClass('choose-active');
        }

        if(check == true) {
            $('#form-multi').submit();
        } else {
            alert('Merci de choisir un mode de paiement pour TOUTE les échéances !');
        }
    });

});