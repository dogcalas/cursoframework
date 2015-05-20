var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestTipoPrograma',

    enableQuickTips: true,

    paths: {
        'GestTipoPrograma': '../../views/js/gesttipoprograma/app'
    },

    controllers: ['TipoPrograma'],

    launch: function () {
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        UCID.portal.cargarEtiquetas('gesttipoprograma', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'tipoprogramalist',
                        tbar: Ext.widget('tipoprogramalisttbar')
                    }
                ]
            })
        });

    }
})
