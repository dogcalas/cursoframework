var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    paths: {
        'PracWin': '../../views/js/gestpracticas/app',
        'GestNotas': '../../views/js/gestnotas/app',
        'GestConv': '../../views/js/gestconvalidaciones/app',
        'GestMatxPensum': '../../../mat/views/js/gestmatxpensum/app',
        'GestEmpresas': '../../../nomencladores/views/js/gestempresas/app'
    },
    enableQuickTips: true,
    appFolder: 'app',
    models: [
        'Practica',
        'SumaPasantias',
        'PracWin.model.Periodo',
        'GestNotas.model.Alumnos',
        'PracWin.model.Anno',
        'GestEmpresas.model.empresa'
    ],
    stores: [
        'stPracticas',
        'stSuma',
        'GestNotas.store.Alumnos',
        'PracWin.store.Anno',
        'PracWin.store.Periodo',
        'GestEmpresas.store.stEmpresas'
    ],
    views: [
        'PracticasList',
        'SearchEmpresa',
        'editPractica',
        'GestConv.view.EnfasisFilter',
        'GestConv.view.StudentInfo',
        'GestNotas.view.nota.WindowSearch',
        'GestNotas.view.nota.AlumnoList'

    ],
    name: 'PracWin',
    controllers: [
        'Practicas'
    ],

    launch: function() {
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        UCID.portal.cargarEtiquetas('gestpracticas', function(){
        Ext.create('Ext.container.Viewport', {
                layout:'fit',
            items: [
                Ext.create('PracWin.view.PracticasList')
                ]
        })
});
    }

});
