var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'App',

    enableQuickTips: true,

    paths: {
        'App': '../../views/js/gesttipoobservacion/app'
    },
    
    views: [
        'App.view.observ.ObservList',
        'App.view.observ.ObservEdit',
        'App.view.observ.ObservListToolBar'
    ],
    stores: [
        'App.store.stObserv'
    ],
    models: [
        'App.model.Observ'
    ],

   controllers: ['Observ'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gesttipoobservacion', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'observlist',
                        tbar: Ext.widget('observlisttbar')
                    }
                ]
            })
        });

    }
})
