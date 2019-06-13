require(['jquery'],function($){
    $(document).ready(function(){
        init();
        doOnLoad();


//            $('input[name="seller"]').prev('input').on('blur', function(){
        $('#seller_btn').on('click', function(){
            var user_id =  $('input[name="seller"]').val();
            if (user_id != "" || user_id != 0) {
                $("#scheduler_here").css('display', 'block');
                changeCalendar(user_id);
            } else {
                $("#scheduler_here").css('display', 'none');
            }
        });

        $('#light-modal-contact ').hide();

        $("#new_contact").on('change', function () {
            var checkbox = $(this);
            if (checkbox.is(':checked')) {
                $('#light-modal-contact ').hide();
                $('#my-select-contact').hide();

                $('#update_contact').hide();
                $('#save_contact').show();

                $('#modal-contact').show();
                $(':input','#contact_form')
                    .not(':button, :submit, :reset, :hidden')
                    .val('')
                    .removeAttr('checked')
                    .removeAttr('selected');

                $(':input','.dhx_cal_light.dhx_cal_light_wide.dhx_cal_light_rec')
                    .not(':button, :submit, :reset')
                    .val('')
                    .removeAttr('checked')
                    .removeAttr('selected');

                $('input[name="contact_name"]').val('');

            } else {
                $('#my-select-contact').show();
                $('#update_contact').show();
                $('#save_contact').hide();
                $('input[name="contact_name"]').prev('input').trigger('blur');
            }
        });

        $('#light-modal-contact').hide();

        $('#use_contact').on('click', function (e) {
            e.preventDefault();

            $('#light-modal-contact p:first').empty();
            $('#light-modal-contact p:first').append('<span>'+$('input[name="contact_civ"]').val()+'</span></br>');
            $('#light-modal-contact p:first').append('<span>'+$('input[name="contact_nom"]').val()+'</span></br>');
            $('#light-modal-contact p:first').append('<span>'+$('input[name="contact_prenom"]').val()+'</span>');

            $('#modal-contact').hide();
            $('#light-modal-contact').show();

            $('#light-modal-contact ').dialog();

        });

        $('input[name="contact_name"]').prev('input').on('focus',function () {
            $('#light-modal-contact').hide();
        });

        $('input[name="contact_name"]').prev('input').blur(function () {
            $('#modal-contact').show();
            // Récupère l'id du contact
            contact_id = $('input[name="contact_name"]').val();
            // Requête ajax pour récupérer les data contact
            $.ajax({
                url: "../calendar/ajax/contact_details.php",
                type:'GET',
                data: {'contact_id': contact_id},
                dataType:'json',
                success: function (response) {

                    $('input[name="contact_civ"]').val(response[0].email);
                    $('input[name="contact_nom"]').val(response[0].firstname);
                    $('input[name="contact_prenom"]').val(response[0].lastname);
                    $('input[name="contact_adr1"]').val(response[0].addr1);
                    $('input[name="contact_adr2"]').val(response[0].addr2);
                    $('input[name="contact_adr3"]').val(response[0].addr3);

                    $('input[name="contact_cp"]').val(response[0].zip_code);
                    $('input[name="contact_ville"]').val(response[0].city);

                    $('input[name="contact_tel1"]').val(response[0].tel1);
                    $('input[name="contact_tel2"]').val(response[0].tel2);
                    $('input[name="contact_tel3"]').val(response[0].tel3);


                    $('input[name="contact_mail"]').val(response[0].email);

                },
                error:function (response) {
                    console.log(response)
                }
            });
        });

        $('input[name="seller"]').prev('input').blur(function () {
            // Récupère l'id du contact
//                seller_id = $('input[name="seller"]').val();
//                // Requête ajax pour récupérer les data contact
//                $.ajax({
//                    url: "../calendar/ajax/seller.php",
//                    type:'GET',
//                    data: {'seller_id': seller_id},
//                    dataType:'json',
//                    success: function (response) {
//
//
//                    },
//                    error:function (response) {
//                        console.log(response)
//                    }
//                });
        });

    });

});