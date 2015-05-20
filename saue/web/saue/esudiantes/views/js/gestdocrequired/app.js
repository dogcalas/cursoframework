var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestDocRequired',

    enableQuickTips: true,

    paths: {
        'GestDocRequired': '../../views/js/gestdocrequired/app'
    },

    controllers: ['DocRequired'],

    launch: function () {
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        UCID.portal.cargarEtiquetas('gestdocrequired', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'docrequiredlist',
                        tbar: Ext.widget('docrequiredlisttbar')
                    }
                ]
            })
        });

    }
})
