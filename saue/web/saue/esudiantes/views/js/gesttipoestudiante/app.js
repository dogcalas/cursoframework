var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestTipoEstudiante',

    enableQuickTips: true,

    paths: {
        'GestTipoEstudiante': '../../views/js/gesttipoestudiante/app'
    },

    controllers: ['TipoEstudiante'],

    launch: function () {
		UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
        UCID.portal.cargarEtiquetas('gesttipoestudiante', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'tipoestudiantelist',
                        tbar: Ext.widget('tipoestudiantelisttbar')
                    }
                ]
            })
        });

    }
})
