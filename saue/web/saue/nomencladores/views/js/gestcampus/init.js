var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'App',

    enableQuickTips: true,

    paths: {
        'App': '../../views/js/gestcampus/app'
    },
    
    views: [
        'App.view.campus.CampusList',
        'App.view.campus.CampusEdit',
        'App.view.campus.CampusListToolBar'
    ],
    stores: [
        'App.store.stCampus'
    ],
    models: [
        'App.model.Campus'
    ],

   controllers: ['Campus'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestcampus', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'campuslist',
                        tbar: Ext.widget('campuslisttbar')
                    }
                ]
            })
        });

    }
})
