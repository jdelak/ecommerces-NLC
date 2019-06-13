var config = {
    paths: {
        'jquery.bootstrap': [
            'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min',
            'Ntic_Common/bootstrap'
        ],
        'jquery.dataTable': [
            'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min',
            'Ntic_Common/dataTable'
        ]
    },
    shim: {
        'jquery.bootstrap': {
            'deps': ['jquery']
        },
        'jquery.dataTable': {
            'deps': ['jquery']
        },
        'payform': {
            deps: ['jquery']
        }
    }
    
};