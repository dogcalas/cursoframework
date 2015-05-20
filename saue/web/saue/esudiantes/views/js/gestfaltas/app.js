Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'GestFaltas.controller': '../../views/js/gestfaltas/app/controller',
        'GestFaltas.view': '../../views/js/gestfaltas/app/view',
        'GestFaltas.store': '../../views/js/gestfaltas/app/store',
        'GestFaltas.model': '../../views/js/gestfaltas/app/model',
        'GestNotas.view': '../../views/js/gestnotas/app/view',
        'GestNotas.store': '../../views/js/gestnotas/app/store',
        'GestNotas.model': '../../views/js/gestnotas/app/model',
        'GestNotas.controller': '../../views/js/gestnotas/app/controller'
    }
});

Ext.require(
    [
        'Ext.container.Viewport',
        'GestFaltas.*'
    ]
);

var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestFaltas',

    enableQuickTips: true,
    appFolder: 'app',
    controllers: ['Faltas'],

    launch: function () {
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        UCID.portal.cargarEtiquetas('gestfaltas', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'border',
                items: [
                    {
                        xtype: 'faltalist',
                        tbar: Ext.widget('faltalisttbar'),
                        flex: 2,
                        region: "north"
                    },
                    {
                        xtype: 'desgloselist',
                        //flex: 1,
                        title:'Histórico de faltas',
                        region: "center",
                        hidden: true
                    }
                ]
            })
        });
    }
});
