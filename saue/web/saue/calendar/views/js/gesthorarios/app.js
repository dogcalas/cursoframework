var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestHorarios',

    enableQuickTips: true,

    paths: {
        'GestHorarios': '../../views/js/gesthorarios/app'
    },

    controllers: ['Horarios'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gesthorarios', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'horarioslist',
                        tbar: Ext.widget('horarioslisttbar')
                    }
                ]
            })
        });

    }
})
