Ext.define('Sancion.view.EditSancion', {
    extend: 'Ext.window.Window',
    alias: 'widget.editsancion',

    height: 350,
    width: 480,
    layout: {
        type: 'border'
    },
    title: '',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'form',
                    region: 'center',
                    frame: true,
                    bodyPadding: 10,
                    title: '',
                    items: [
                        {
                            xtype: 'studentinfo'
                        },
                        {
                            xtype: 'datefield',
                            anchor: '50%',
                            fieldLabel: 'Fecha de inicio',
                            allowBlank:false,
                            name: 'fechainicio',
                            listeners: {
                                'select': function (field, value) {
                                    Ext.getCmp('fechafin').setMinValue(value);
                                },
                                'change': function (field, value) {
                                    Ext.getCmp('fechafin').setMinValue(value);
                                }
                            }
                        },
                        {
                            xtype: 'hiddenfield',
                            id:'idalumnofield',
                            name: 'idalumno'
                        },
                        {
                            xtype: 'hiddenfield',
                            id:'nombrefield',
                            name: 'nombre'
                        },
                        {
                            xtype: 'hiddenfield',
                            id:'apellidosfield',
                            name: 'apellidos'
                        },
                        {
                            xtype: 'hiddenfield',
                            id:'cedulafield',
                            name: 'cedula'
                        },
                        {
                            xtype: 'datefield',
                            anchor: '50%',
                            fieldLabel: 'Fecha de fin',
                            allowBlank:false,
                            name: 'fechafin',
                            id: 'fechafin'
                        },
                        {
                            xtype: 'textareafield',
                            anchor: '100% 45%',
                            name: 'descripcion',
                            allowBlank:false,
                            fieldLabel: 'Descripción'
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
                                id: 'idBtnSAplicar',
                                icon: perfil.dirImg + 'aplicar.png',
                                iconCls: 'btn',
                                text: perfil.etiquetas.lbBtnAplicar,
                                action: 'aplicar'
                            },
                            {
                                id: 'idBtnSAceptar',
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