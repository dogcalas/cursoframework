var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestCoRequisitos',

    enableQuickTips: true,

    paths: {
        'GestCoRequisitos': '../../views/js/gestcorequisitos/app',
        'GestMaterias': '../../views/js/gestmaterias/app'
    },

    controllers: ['GestCoRequisitos'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestcorequisitos', function () {
            Ext.create('Ext.container.Viewport', {
                id: 'idvcorequisitos',
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                items: [
                    {
                        xtype: 'corequisitos_materia_grid',
                        //perfil: perfil,
                        flex: 1
                    },
                    {
                        xtype: 'corequisitolist',
                        //perfil: perfil,
                        flex: 1
                    }
                ]
            })
        });
    }
})
