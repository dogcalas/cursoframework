var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'CredxArea',

    enableQuickTips: true,

    paths: {
        'CredxArea': '../../views/js/credxarea/app',
        'GestCarreras': '../../views/js/gestcarreras/app',
        'GestEnfasis': '../../views/js/gestenfasis/app'
    },

    controllers: ['CredsxAreaC'],

    launch: function () {
        UCID.portal.cargarEtiquetas('credxarea', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'credxarea_grid'
                    }
                ]
            });
        });

    }
});
