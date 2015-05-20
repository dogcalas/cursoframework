var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestAreas',

    enableQuickTips: true,

    paths: {
        'GestAreas': '../../views/js/gestareas/app'
    },

    controllers: ['Areas'],

    launch: function () {
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        UCID.portal.cargarEtiquetas('gestareas', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'area_grid'
                    }
                ]
            })
        });
    }
})
