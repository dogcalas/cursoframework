var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
        name: 'GestMateriaxMencion',

        enableQuickTips: true,

        paths: {
            'GestMateriaxMencion': '../../views/js/gestmencion/app'
        },

        controllers: ['MateriaxMencionC'],

        launch: function () {
            UCID.portal.cargarEtiquetas('gestmencion', function () {
                Ext.create('Ext.container.Viewport', {
                    layout: {
                        type: 'hbox',
                        align: 'stretch',
                        defaultType: 'container'
                    },
                    items: [
                        {
                            flex: 1,
                            layout: 'anchor',
                            padding: '10 5 10 10',
                            border: 0,
                            items: [
                                {
                                    xtype: 'materiaxmencion_materia_grid',
                                    anchor: '100%, 100%',

                                    viewConfig: {
                                        plugins: {
                                            ptype: 'gridviewdragdrop',
                                            dragGroup: 'firstGridDDGroup',
                                            //dropGroup: 'secondGridDDGroup',
                                            pluginId: 'id_materia_mencion_drag',
                                            dragText: 'Arrastre y suelte para agregar'//Etiqueta
                                        }
                                    }
                                }
                            ]
                        },
                        {
                            flex: 1,
                            layout: 'anchor',
                            padding: '10 10 10 5',
                            border: 0,
                            items: [
                                {
                                    xtype: 'mencion_combo'
                                },
                                {
                                    xtype: 'materiaxmencion_grid',
                                    anchor: '100%, -45',
                                    selModel: Ext.create('Ext.selection.RowModel', {
                                        mode: 'SINGLE'
                                    }),
                                    viewConfig: {
                                        plugins: {
                                            ptype: 'gridviewdragdrop',
                                            dragGroup: 'secondGridDDGroup',
                                            dropGroup: 'firstGridDDGroup',
                                            pluginId: 'id_materia_mencion_drop'
                                        }
                                    }
                                }
                            ]
                        }
                    ]
                })
            })
        }
    }
)
