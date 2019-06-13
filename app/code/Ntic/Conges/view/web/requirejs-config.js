var config = {
    paths: {
        'datatable': "Ntic_Conges/js/jquery.dataTables.min",
        'moment':  "Ntic_Conges/js/moment",
        'datetime': "Ntic_Conges/js/datetime-moment"
    },
    shim: {
        'perso': {
            deps: ['jquery']
        },
        'moment': {
            deps: ['jquery']
        },
        'datetime': {
            deps: ['moment']
        },

    }
};