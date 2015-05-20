var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'App',

    enableQuickTips: true,

    paths: {
        'App': '../../views/js/gesttipodiscapacidad/app'
    },
    
    views: [
        'App.view.tdisc.TDiscList',
        'App.view.tdisc.TDiscEdit',
        'App.view.tdisc.TDiscListToolBar'
    ],
    stores: [
        'App.store.stTDisc'
    ],
    models: [
        'App.model.TDisc'
    ],

   controllers: ['TDisc'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gesttipodiscapacidad', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'tdisclist',
                        tbar: Ext.widget('tdisclisttbar')
                    }
                ]
            })
        });

    }
})
