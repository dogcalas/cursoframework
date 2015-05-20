Ext.define('GestEmpresas.view.winAdicionar', {
    extend: 'Ext.window.Window',
    alias: 'widget.winadicionar',

    height: 385,
    width: 300,
    resizable: false,
    initComponent: function () {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'form',
                    frame: true,
                    bodyPadding: 10,
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Ruc',
                            anchor: '100%',
                            name: 'ruc',
                            labelAlign: 'top',
                            allowBlank: false,
                            validator: function (val) {
                                if (!/^\d+$/.test(val)) {
                                    return 'Debe contener solo n&uacute;meros';
                                } else {
                                    return true;
                                }
                            }
                        },
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            fieldLabel: 'Denominaci&oacute;n',
                            size: 100,
                            labelAlign: 'top',
                            name: 'descripcion',
                            allowBlank: false
                        },
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            fieldLabel: 'Actividad',
                            size: 100,
                            labelAlign: 'top',
                            name: 'actividad',
                            allowBlank: false
                        },
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            fieldLabel: 'Tel&eacute;fono',
                            name: 'telefono',
                            labelAlign: 'top',
                            allowBlank: false
                        },
                        {
                            xtype: 'textareafield',
                            anchor: '100%',
                            labelAlign: 'top',
                            width: 516,
                            fieldLabel: 'Direcci&oacute;n',
                            name: 'direccion',
                            allowBlank: false
                        },
                        {
                            xtype: 'hidden',
                            anchor: '100%',
                            fieldLabel: 'Label',
                            name: 'idempresa'
                        },
                        {
                            id: 'estado',
                            xtype: 'checkbox',
                            fieldLabel: 'Activado',
                            checked: true,
                            inputValue: true,
                            uncheckedValue: false,
                            name: 'estado'
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