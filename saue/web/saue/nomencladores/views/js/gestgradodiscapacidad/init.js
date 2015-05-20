var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'App',

    enableQuickTips: true,

    paths: {
        'App': '../../views/js/gestgradodiscapacidad/app'
    },
    
    views: [
        'App.view.gdisc.GDiscList',
        'App.view.gdisc.GDiscEdit',
        'App.view.gdisc.GDiscListToolBar'
    ],
    stores: [
        'App.store.stGDisc'
    ],
    models: [
        'App.model.GDisc'
    ],

   controllers: ['GDisc'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestgradodiscapacidad', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'gdisclist',
                        tbar: Ext.widget('gdisclisttbar')
                    }
                ]
            })
        });

    }
})
