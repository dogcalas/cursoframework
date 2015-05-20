 Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'RegMaterias.controller': '../../views/js/regmaterias/app/controller',
        'RegMaterias.view': '../../views/js/regmaterias/app/view',
        'RegMaterias.store': '../../views/js/regmaterias/app/store',
        'RegMaterias.model': '../../views/js/regmaterias/app/model',
        'GestNotas.view': '../../views/js/gestnotas/app/view',
        'GestNotas.store': '../../views/js/gestnotas/app/store',
        'GestNotas.model': '../../views/js/gestnotas/app/model',
        'GestNotas.controller': '../../views/js/gestnotas/app/controller',
		'RegIdiomas.view': '../../views/js/regidiomas/app/view',
		'RegIdiomas.store': '../../views/js/regidiomas/app/store',
        'RegIdiomas.model': '../../views/js/regidiomas/app/model',
		'GestCursos.store': '../../../../saue/curso/views/js/gestcursos/app/store',
		'GestCursos.model': '../../../../saue/curso/views/js/gestcursos/app/model'
    }
})

Ext.require(
    [
        'Ext.container.Viewport',
        'RegMaterias.*'
    ]
);

var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'RegMaterias',

    enableQuickTips: true,
    appFolder: 'app',
    controllers: ['CRegMaterias'],

    launch: function () {
        UCID.portal.cargarEtiquetas('regmaterias', function(){
        Ext.create('Ext.container.Viewport', {
            layout: 'fit',
            items: [
                {
                    xtype: 'materialist',
                    tbar: Ext.widget('tbmateria')
                }
            ]
        })
});
    }
}) 
