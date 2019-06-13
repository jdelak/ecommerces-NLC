var config = {
    path: {
        'owl.carousel': "Ntic_Diagnostic/js/owl.carousel",
        'jquery.bootstrap': [
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min',
            'Ntic_Common/bootstrap'
        ],
    },
    shim: {
        'owl.carousel': {
            deps: ['jquery']
        },
        'jquery.bootstrap': {
            'deps': ['jquery']
        }
    }
};