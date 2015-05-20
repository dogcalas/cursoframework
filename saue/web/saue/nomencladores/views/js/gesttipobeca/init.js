var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'App',

    enableQuickTips: true,

    paths: {
        'App': '../../views/js/gesttipobeca/app'
    },
    
    views: [
        'App.view.tbeca.TBecaList',
        'App.view.tbeca.TBecaEdit',
        'App.view.tbeca.TBecaListToolBar'
    ],
    stores: [
        'App.store.stTBeca'
    ],
    models: [
        'App.model.TBeca'
    ],

   controllers: ['TBeca'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gesttipobeca', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'tbecalist',
                        tbar: Ext.widget('tbecalisttbar')
                    }
                ]
            })
        });

    }
})
