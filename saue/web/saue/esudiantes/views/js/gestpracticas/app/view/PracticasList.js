Ext.define('PracWin.view.PracticasList', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.practicalist',
    layout: {
        type: 'border'
    },
    title: '',
    initComponent: function () {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'container',
                    region: 'north',
                    height: 120,
                    layout: {
                        type: 'hbox',
                        align: 'stretch',
                        defaultType: 'container'
                    },
                    items: [
                        {
                            flex: 1.5,
                            layout: 'anchor',
                            border: 0,
                            padding: '5 0 5 5',
                            items: [Ext.widget('studentinfo')]
                        },
                        {
                            flex: 1,
                            layout: 'anchor',
                            border: 0,
                            padding: '20 0 5 15',
                            items: [
                                {
                                    xtype: 'fieldset',
                                    columnWidth: 3 / 4,
                                    padding: '5 0 5 5',
                                    height: '100%',
                                    layout: 'anchor',
                                    border: 1,
                                    style: {
                                        borderColor: 'red',
                                        borderStyle: 'solid'
                                    },
                                    items: [
                                        {
                                            xtype: 'displayfield',
                                            fieldLabel: 'Carrera',
                                            id: 'studentCarrera',
                                            value: '-'
                                        }, {
                                            xtype: 'displayfield',
                                            fieldLabel: 'Itinerario',
                                            id: 'studentItinerario',
                                            value: '-'
                                        }, {
                                            xtype: 'hiddenfield',
                                            hidden: true,
                                            id: 'idenfasis'
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    xtype: 'gridpanel',
                    flex: 15,
                    region: 'center',
                    title: 'Detalle de pasantías',
                    store: 'stPracticas',
                    id: 'gridPracticas',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            width: 118,
                            dataIndex: 'pasantia',
                            flex: 1,
                            text: 'Tipo de pasantía'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'estado',
                            flex: 1,
                            renderer: function (value) {
                                if (value) {
                                    return perfil.etiquetas.lbStateAprobado;
                                }
                                return perfil.etiquetas.lbStatePendiente;
                            },
                            text: 'Estado'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'empresa',
                            flex: 1,
                            text: 'Empresa'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'horas',
                            text: 'Horas'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'proyecto',
                            text: 'Proyecto'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'responsable',
                            text: 'Responsable'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'idperiododocente',
                            hidden: true
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'periodo_practica',
                            hidden: true
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'codperiodo',
                            hidden: true
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'anno_practica',
                            hidden: true
                        }
                    ],
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'top',
                            id: 'practicastool',
                            disabled: true,
                            items: [
                                {
                                    xtype: 'button',
                                    action: 'adicionar',
                                    id: 'adicionarBtn',
                                    text: perfil.etiquetas.lbBtnAdicionar,
                                    icon: perfil.dirImg + 'adicionar.png',
                                    iconCls: 'btn'
                                },
                                {
                                    xtype: 'button',
                                    action: 'modificar',
                                    id: 'modificarBtn',
                                    text: perfil.etiquetas.lbBtnModificar,
                                    icon: perfil.dirImg + 'modificar.png',
                                    iconCls: 'btn',
                                    disabled: true
                                },
                                {
                                    xtype: 'button',
                                    action: 'eliminar',
                                    id: 'eliminarBtn',
                                    text: perfil.etiquetas.lbBtnEliminar,
                                    icon: perfil.dirImg + 'eliminar.png',
                                    iconCls: 'btn',
                                    disabled: true
                                },
                                {
                                    xtype: 'button',
                                    action: 'aprobar',
                                    id: 'aprobarBtn',
                                    text: perfil.etiquetas.lbBtnAprobar,
                                    icon: perfil.dirImg + 'validaryaceptar.png',
                                    iconCls: 'btn',
                                    disabled: true
                                }
                            ]
                        },
                        {
                            xtype: 'pagingtoolbar',
                            id: 'paginator',
                            store: 'stPracticas',
                            dock: 'bottom',
                            disabled: true,
                            width: 360,
                            displayInfo: true
                        }
                    ]
                },
                {
                    xtype: 'gridpanel',
                    region: 'south',
                    flex: 7,
                    title: 'Suma de horas por tipo de pasantías',
                    store: 'stSuma',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'descripcion',
                            flex: 2,
                            text: 'Tipo de pasantía'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'horas',
                            flex: 1,
                            text: 'Horas'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});