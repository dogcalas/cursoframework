var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
    name: 'GestPreRequisitos',

    enableQuickTips: true,

    paths: {
        'GestPreRequisitos': '../../views/js/gestprerequisitos/app',
        'GestMaterias': '../../views/js/gestmaterias/app'
    },

    controllers: ['GestPreRequisitos'],

    launch: function () {
        UCID.portal.cargarEtiquetas('gestprerequisitos', function () {
            Ext.create('Ext.container.Viewport', {
                id: 'idvprerequisitos',
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                items: [
                    {
                        xtype: 'prerequisitos_materia_grid',
                        flex: 1
                    },
                    {
                        xtype: 'prerequisitolist',
                        flex: 1
                    }
                ]
            })
        });
    }
})
