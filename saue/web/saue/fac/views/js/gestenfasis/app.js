var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestEnfasis',

    enableQuickTips: true,

    paths: {
        'GestEnfasis': '../../views/js/gestenfasis/app',

        'GestCarreras': '../../views/js/gestcarreras/app'
    },

    controllers: ['Enfasis'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestenfasis', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'enfasilist'
                    }
                ]
            })
        });
    }
})
