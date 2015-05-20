var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestCursos',

    enableQuickTips: true,

    paths: {
        'GestCursos': '../../views/js/gestcursos/app'
    },

    controllers: ['CursosC'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestcursos', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'curso_grid'
                    }
                ]
            });
        });

    }
});
