Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'GestNotas.controller': '../../views/js/gestnotas/app/controller',
        'GestNotas.view': '../../views/js/gestnotas/app/view',
        'GestNotas.store': '../../views/js/gestnotas/app/store',
        'GestNotas.model': '../../views/js/gestnotas/app/model'
    }
});

Ext.require(
    [
        'Ext.container.Viewport',
        'GestNotas.*'
    ]
);

var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestNotas',

    enableQuickTips: true,
    appFolder: 'app',
    controllers: ['Notas'],

    launch: function () {
		UCID.portal.cargarEtiquetas('gestnotas', function(){
        Ext.create('Ext.container.Viewport', {
            layout: 'border',
            items: [
                {
                    xtype: 'notalist',
                    region:"north",
                    flex:2,
                    tbar: Ext.widget('notalisttbar')
                },
                {
                    xtype: 'notaloglist',
                    //flex:1,
                    region:"center",
                    title: 'Histórico de notas',
                    hidden: true
                }
            ]
        })
});
    }
})
