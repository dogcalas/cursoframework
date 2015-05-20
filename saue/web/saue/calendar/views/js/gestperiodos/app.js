var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestPeriodos',

    enableQuickTips: true,

    paths: {
        'GestPeriodos': '../../views/js/gestperiodos/app'
    },

    controllers: ['Periodos'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestperiodos', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'periodolist',
                        tbar: Ext.widget('periodolisttbar')
                    }
                ]
            })
        });

    }
})
