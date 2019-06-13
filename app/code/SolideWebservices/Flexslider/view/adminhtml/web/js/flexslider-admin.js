require(['jquery', 'domReady!', 'solidewebservices/spectrum'], function($){
    'use strict';

    /* initial show/hide elements */
    var flexsliderEnabled = $('#flexslider_general_enabled').val();
    toggleFieldsets(flexsliderEnabled);

    /* hide or show fieldsets based on changing extension status */
    /* hide or show elements based on changing zoomtype */
    $('#flexslider_general_enabled').change(function() {
        flexsliderEnabled = $(this).val();
        toggleFieldsets(flexsliderEnabled);
    });

    function toggleFieldsets(status){
        switch(status){
            case '0':
                $('#flexslider_advanced_settings-link').closest('.section-config').hide();
                break;
            case '1':
                $('#flexslider_advanced_settings-link').closest('.section-config').show();
                break;
        }
    }

    $('.colorpicker').spectrum({
        showInput: true,
        showAlpha: true,
        showInitial: true,
        showInput: true,
        showButtons: false,
        clickoutFiresChange: true,
        preferredFormat: "rgb",
    });

});
