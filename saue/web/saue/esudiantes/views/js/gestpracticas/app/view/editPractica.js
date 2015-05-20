Ext.define('PracWin.view.editPractica', {
    extend: 'Ext.window.Window',
    alias: 'widget.editpractica',
    height: 390,
    width: 462,
    layout: 'fit',
    resizable: false,
    title: '',

    initComponent: function () {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'form',
                    frame: true,
                    bodyPadding: 5,
                    title: '',
                    items: [
                        {
                            xtype: 'container',
                            layout: {
                                type: 'hbox'
                            },
                            items: [
                                {
                                    xtype: 'combo',
                                    fieldLabel: 'Año',
                                    width: '20%',
                                    id: 'anno_practica',
                                    name: 'anno_practica',
                                    labelAlign: 'top',
                                    allowBlank: false,
                                    valueField: 'anno',
                                    displayField: 'anno',
                                    store: 'PracWin.store.Anno',
                                    queryMode: 'local'
                                },
                                {
                                    xtype: 'combo',
                                    padding: '0 0 0 20',
                                    width: '80%',
                                    id: 'periodo_practica',
                                    name: 'periodo_practica',
                                    fieldLabel: 'Período',
                                    labelAlign: 'top',
                                    allowBlank: false,
                                    valueField: 'idperiododocente',
                                    displayField: 'periodo',
                                    store: 'PracWin.store.Periodo',
                                    queryMode: 'local'
                                }
                            ]
                        },
                        {
                            xtype: 'triggerfield',
                            id: 'selectempresa',
                            anchor: '100%',
                            triggerCls: Ext.baseCSSPrefix + 'form-search-trigger',
                            fieldLabel: 'Empresa',
                            labelAlign: 'top',
                            name: 'empresa',
                            allowBlank: false,
                            onTriggerClick: function () {
                                this.fireEvent("searchclick", this);
                            }
                        },
                        {
                            xtype: 'container',
                            layout: 'hbox',
                            items: [
                                {
                                    xtype: 'combobox',
                                    width: '80%',
                                    fieldLabel: 'Tipo',
                                    name: 'idtipopractica',
                                    editable: false,
                                    id: 'tipopractica',
                                    queryMode: 'local',
                                    labelAlign: 'top',
                                    store: 'stSuma',
                                    allowBlank: false,
                                    forceSelection: true,
                                    displayField: 'descripcion',
                                    valueField: 'idtipopractica',
                                    listeners: {
                                        change: function () {
                                            if (this.getValue() == 1000002) {
                                                Ext.getCmp('proyecto').show();
                                                Ext.getCmp('responsable').show();
                                            } else {
                                                Ext.getCmp('proyecto').hide();
                                                Ext.getCmp('responsable').hide();
                                            }
                                        }
                                    }
                                },
                                {
                                    xtype: 'numberfield',
                                    width: '20%',
                                    labelAlign: 'top',
                                    allowBlank: false,
                                    fieldLabel: 'Horas',
                                    name: 'horas',
                                    minValue: 1,
                                    margin: '0 0 0 20'
                                }
                            ]
                        },
                        {
                            xtype: 'hiddenfield',
                            anchor: '100%',
                            name: 'idempresa',
                            id: 'idempresa'
                        },
                        {
                            xtype: 'hiddenfield',
                            anchor: '100%',
                            name: 'idpractica',
                            id: 'idpractica'
                        },
                        {
                            xtype: 'hiddenfield',
                            anchor: '100%',
                            value: false,
                            name: 'estado'
                        },
                        {
                            xtype: 'hiddenfield',
                            anchor: '100%',
                            name: 'pasantia',
                            id: 'pasantia'
                        },
                        {
                            xtype: 'hiddenfield',
                            anchor: '100%',
                            name: 'idperiododocente',
                            id: 'idperiododocente'
                        },
                        {
                            xtype: 'container',
                            layout: {
                                type: 'hbox'
                            },
                            items: [
                                {
                                    xtype: 'textfield',
                                    width: '45%',
                                    fieldLabel: 'Nombre proyecto',
                                    labelAlign: 'top',
                                    hidden: true,
                                    name: 'proyecto',
                                    id: 'proyecto'

                                },
                                {
                                    xtype: 'textfield',
                                    width: '55%',
                                    hidden: true,
                                    labelAlign: 'top',
                                    margin: '0 0 0 10',
                                    fieldLabel: 'Profesor responsable',
                                    name: 'responsable',
                                    id: 'responsable'
                                }
                            ]
                        },
                        {
                            xtype: 'container',
                            layout: {
                                type: 'hbox'
                            },
                            items: [
                                {
                                    xtype: 'datefield',
                                    width: '40%',
                                    allowBlank: false,
                                    labelAlign: 'top',
                                    name: 'fechainicio',
                                    editable: false,
                                    fieldLabel: 'Fecha de inicio',
                                    listeners: {
                                        'change': function (field, value) {
                                            if (value) {
                                                Ext.getCmp('fechafin').setMinValue(value);
                                            }
                                        }
                                    }
                                },
                                {
                                    xtype: 'datefield',
                                    padding: '0 0 0 20',
                                    allowBlank: false,
                                    labelAlign: 'top',
                                    editable: false,
                                    width: '40%',
                                    name: 'fechafin',
                                    id: 'fechafin',
                                    fieldLabel: 'Fecha de fin'
                                }
                            ]
                        },
                        {
                            xtype: 'fieldset',
                            title: 'Información adicional',
                            items: [
                                {
                                    xtype: 'container',
                                    layout: {
                                        type: 'hbox'
                                    },
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            width: '50%',
                                            labelAlign: 'top',
                                            fieldLabel: 'Cargo',
                                            name: 'cargo'
                                        },
                                        {
                                            xtype: 'textfield',
                                            width: '50%',
                                            labelAlign: 'top',
                                            fieldLabel: 'Jefe',
                                            padding: '0 0 0 20',
                                            name: 'jefe'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'container',
                                    layout: {
                                        type: 'hbox'
                                    },
                                    items: [
                                        {
                                            xtype: 'textfield',
                                            width: '50%',
                                            padding: '0 0 10 0',
                                            labelAlign: 'top',
                                            fieldLabel: 'Cargo del jefe',
                                            name: 'cargojefe'
                                        },
                                        {
                                            xtype: 'textfield',
                                            width: '50%',
                                            labelAlign: 'top',
                                            padding: '0 0 10 20',
                                            fieldLabel: 'Correo del jefe',
                                            name: 'mailjefe'
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    layout: {
                        pack: 'end',
                        type: 'hbox'
                    },
                    items: [
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
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});