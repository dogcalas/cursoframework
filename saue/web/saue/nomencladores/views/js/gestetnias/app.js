var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestEtnias',

    enableQuickTips: true,

    paths: {
        'GestEtnias': '../../views/js/gestetnias/app'
    },

    controllers: ['Etnia'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestetnias', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'etnialist',
                        tbar: Ext.widget('etnialisttbar')
                    }
                ]
            })
        });

    }
})