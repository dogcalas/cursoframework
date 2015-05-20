var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestTiposMateria',

    enableQuickTips: true,

    paths: {
        'GestTiposMateria': '../../views/js/gesttiposmateria/app'
    },

    controllers: ['TiposMaterias'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gesttiposmateria', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'tiposmaterialist',
                        tbar: Ext.widget('tiposmaterialisttbar')
                    }
                ]
            })
        });

    }
})
