var perfil = window.parent.UCID.portal.perfil;
perfil.etiquetas = Object();

Ext.application({
        name: 'GestMatxPensum',

        enableQuickTips: true,

        paths: {
            'GestMatxPensum': '../../views/js/gestmatxpensum/app'
        },

        controllers: ['MatxPensum'],

        launch: function () {
            UCID.portal.cargarEtiquetas('gestmatxpensum', function () {
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
                            defaults: {
                                anchor: '95%'
                            },
                            items: [
                                {
                                    xtype: 'facultad_combo'
                                },
                                {
                                    xtype: 'carrera_combo'
                                },
                                {
                                    xtype: 'enfasi_combo'
                                },
                                {
                                    xtype: 'matxpensum_materia_grid',
                                    anchor: '100%, -135',

                                    viewConfig: {
                                        plugins: {
                                            ptype: 'gridviewdragdrop',
                                            dragGroup: 'firstGridDDGroup',
                                            //dropGroup: 'secondGridDDGroup',
                                            pluginId: 'id_materia_pensum_grid_drag',
                                            dragText: perfil.etiquetas.lbTxtDrag//'Arrastre y suelte para agregar'//Etiqueta
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
                            defaults: {
                                anchor: '95%'
                            },
                            items: [
                                {
                                    xtype: 'pensum_combo'
                                },
                                {
                                    xtype: 'area_general_combo'
                                },
                                {
                                    xtype: 'area_combo'
                                },/*
                                {
                                 xtype: 'tipo_materia_combo',
                                 anchor: '100%'
                                 },*/
                                {
                                    xtype: 'matxpensum_grid',
                                    anchor: '100%, -135',
                                    selModel: Ext.create('Ext.selection.RowModel', {
                                        mode: 'SINGLE'
                                    }),
                                    title: perfil.etiquetas.lbTlt2,//'Selección de materia seg&uacute;n tipo:',//Eqtiqueta
                                    viewConfig: {
                                        plugins: {
                                            ptype: 'gridviewdragdrop',
                                            dragGroup: 'secondGridDDGroup',
                                            dropGroup: 'firstGridDDGroup',
                                            pluginId: 'id_materia_pensum_grid_drop'
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
