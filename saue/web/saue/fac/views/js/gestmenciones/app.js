var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestMenciones',

    enableQuickTips: true,

    paths: {
        'GestMenciones': '../../views/js/gestmenciones/app',
        'GestPensums': '../../views/js/gestpensum/app',
        'GestCarreras': '../../views/js/gestcarreras/app'
    },

    controllers: ['MencionesC'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestmenciones', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'mencion_grid'
                    }
                ]
            })
        });
    }
})
