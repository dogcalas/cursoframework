var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    enableQuickTips: true,
    appFolder: 'app',
    models: [
        'Sancion',
                'GestNotas.model.Alumnos',
    ],
    controllers: [
        'Sancion'
    ],
    paths: {
        'Sancion': '../../views/js/gestsanciones/app',
        'GestNotas': '../../views/js/gestnotas/app',
        'GestConv': '../../views/js/gestconvalidaciones/app',
        'GestMatxPensum': '../../../mat/views/js/gestmatxpensum/app',
    },
    stores: [
        'stSancion',
        'GestNotas.store.Alumnos',
    ],
    views: [
        'SancionList',
        'EditSancion',
        'GestConv.view.StudentInfo',
        'GestNotas.view.nota.WindowSearch',
        'GestNotas.view.nota.AlumnoList'
    ],
    name: 'Sancion',

    launch: function() {
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        UCID.portal.cargarEtiquetas('gestsanciones', function(){
        Ext.create('Ext.container.Viewport', {
                layout:'fit',
            items: [
                Ext.create('Sancion.view.SancionList')
                ]
        })
});
    }

});
