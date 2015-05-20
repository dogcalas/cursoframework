var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestPasantias',

    enableQuickTips: true,

    paths: {
        'GestPasantias': '../../views/js/gestpasantias/app',

        'GestEnfasis': '../../views/js/gestenfasis/app',
        'GestCarreras': '../../views/js/gestcarreras/app'
    },

    controllers: ['PasantiasC'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestpasantias', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'pasantia_grid'
                    }
                ]
            })
        });
    }
})
