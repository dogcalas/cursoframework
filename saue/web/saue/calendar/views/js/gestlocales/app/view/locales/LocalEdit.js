Ext.define('GestLocales.view.locales.LocalEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.localedit',

    layout: 'fit',
    modal: true,
    resizable: false,
    autoShow: true,
    width: 300,
    height: 300,

    initComponent: function () {
        var me = this;

        me.items = [
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
                        name: 'idaula'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: perfil.etiquetas.lbHdrDescripcion,
                        name: 'aula',
                        labelAlign: 'top',
                        allowOnlyWhitespace: false
                    },
                    {
                        xtype: 'local_tipolocal_combo'
                    },
                    {
                        xtype: 'local_ubicacion_combo'
                    },
                    {
                        xtype: 'numberfield',
                        fieldLabel: perfil.etiquetas.lbHdrCapacidad,//perfil.etiquetas.lbHdrDescripcion,
                        name: 'capacidad',
                        minValue: 0,
                        labelAlign: 'top',
                        allowBlank: false
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
            }
        ];

        me.buttons = [
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

        me.callParent(arguments);
    }
})