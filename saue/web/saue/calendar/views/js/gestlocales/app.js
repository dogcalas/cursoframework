var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestLocales',

    enableQuickTips: true,

    paths: {
        'GestLocales': '../../views/js/gestlocales/app'
    },

    controllers: ['Locales'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestlocales', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'locallist',
                        tbar: Ext.widget('locallisttbar')
                    }
                ]
            })
        });

    }
})
