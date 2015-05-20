Ext.define('GestPeriodos.view.periodos.PeriodosEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.periodoedit',
    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 300,

    initComponent: function () {
        this.items = [
            {
                xtype: 'form',
                fieldDefaults: {
                    msgTarget: 'side',
                    anchor: '100%'
                },
                defaults: {
                    padding: '5'
                },
                items: [
                    {
                        xtype: 'hidden',
                        name: 'idperiododocente'
                    },
                    {
                        xtype: 'fieldset',
                        title: 'Código',
//                        defaults: {width: '80%'},
                        items: [
                            {
                                xtype: 'container',
                                layout: 'column',
                                items: [
                                    {
                                        xtype: 'container',
                                        columnWidth: .5,
                                        layout: 'anchor',
                                        items: [
                                            {
                                                xtype: 'periodo_anio_combo'
                                            }
                                        ]
                                    },
                                    {
                                        xtype: 'container',
                                        columnWidth: .5,
                                        layout: 'anchor',
                                        items: [
                                            {
                                                xtype: 'periodo_ubicacion_combo'
                                            }
                                        ]

                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: perfil.etiquetas.lbHdrDescripcion,
                        name: 'descripcion',
                        labelAlign: 'top',
                        allowOnlyWhitespace: false,
                        allowBlank: false,
                        regex: /^\D[\sa-zA-Z0-9\W]*$/ //revisar esta expresion regular
                    },
                    {
                        xtype: 'periodo_tipoperiodo_combo'
                    },
                    {
                        xtype: 'container',
                        layout: 'column',
                        items: [
                            {
                                xtype: 'container',
                                columnWidth: .5,
                                layout: 'anchor',
                                items: [
                                    {
                                        xtype: 'datefield',
                                        fieldLabel: 'Fecha inicio',
                                        name: 'fecha_ini',
                                        id: 'fecha_ini1',
                                        labelAlign: 'top',
                                        disabled: true,
                                        editable: false,
                                        allowBlank: false,
                                        listeners: {
                                            'select': function (field, value) {
                                                var fecha = new Date(value),
                                                    idtipoperiodo = Ext.getCmp('idPeriodoTipoPeriodoCombo').getValue(),
                                                    index = Ext.getCmp('idPeriodoTipoPeriodoCombo').store.findExact('idtipo_periododocente', idtipoperiodo),
                                                    semanas = Ext.getCmp('idPeriodoTipoPeriodoCombo').store.data.items[index].raw.duracion,
                                                    duracion = semanas * 7,
                                                    fecha_fin = Ext.Date.add(fecha, Ext.Date.DAY, duracion);

                                                Ext.getCmp('fecha_fin1').setValue(fecha_fin);
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'numberfield',
                                        fieldLabel: 'Notas',
                                        name: 'cant_notas',
                                        labelAlign: 'top',
                                        minValue: 1
                                    },
                                    {
                                        id: 'estado',
                                        xtype: 'checkbox',
                                        fieldLabel: perfil.etiquetas.lbHdrEstado,
                                        checked: true,
                                        inputValue: true,
                                        uncheckedValue: false,
                                        name: 'estado'
                                    }
                                ]
                            },
                            {
                                xtype: 'container',
                                columnWidth: .5,
                                layout: 'anchor',
                                items: [
                                    {
                                        xtype: 'datefield',
                                        fieldLabel: 'Fecha fin',
                                        name: 'fecha_fin',
                                        id: 'fecha_fin1',
                                        labelAlign: 'top',
                                        padding: '0 0 0 10',
                                        disabled: true,
                                        editable: false,
                                        allowBlank: false
                                    }
                                ]

                            }
                        ]
                    }
                ]
            }
        ];

        this.buttons = [
            {
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this,
                handler: this.close
            },
            {
                id: 'idBtnAplicar',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
                action: 'aplicar'
            },
            {
                id: 'idBtnAceptar',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        this.callParent(arguments);
    }
})
