var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestMaterias',

    enableQuickTips: true,

    paths: {
        'GestMaterias': '../../views/js/gestmaterias/app',
        'GestAreas': '../../views/js/gestareas/app'
    },

    controllers: ['Materias'],

    /*init: function () {
     UCID.portal.cargarEtiquetas('gestmaterias');
     },*/

    launch: function () {
        UCID.portal.cargarEtiquetas('gestmaterias', function () {
            Ext.create('Ext.container.Viewport', {
                layout: 'fit',
                items: [
                    {
                        xtype: 'materialist'
                    }
                ]
            })
        });
    }
})
