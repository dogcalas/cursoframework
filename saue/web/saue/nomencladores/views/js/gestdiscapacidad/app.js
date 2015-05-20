var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestDiscapacidad',

    enableQuickTips: true,

    paths: {
        'GestDiscapacidad': '../../views/js/gestdiscapacidad/app'
    },

    controllers: ['Discapacidad'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestdiscapacidad', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'discapacidadlist',
                        tbar: Ext.widget('discapacidadlisttbar')
                    }
                ]
            })
        });

    }
})