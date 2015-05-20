var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestTipoLocales',

    enableQuickTips: true,

    paths: {
        'GestTipoLocales': '../../views/js/gesttipolocales/app'
    },

    controllers: ['TipoLocales'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gesttipolocales', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'tipolocallist',
                        tbar: Ext.widget('tipolocallisttbar')
                    }
                ]
            })
        });

    }
})