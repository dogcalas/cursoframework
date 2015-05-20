var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestPensums',

    enableQuickTips: true,

    paths: {
        'GestPensums': '../../views/js/gestpensum/app',

        'GestCarreras': '../../views/js/gestcarreras/app'
    },

    controllers: ['Pensums'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestpensum', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'pensumlist'
                    }
                ]
            })
        });
    }
})
