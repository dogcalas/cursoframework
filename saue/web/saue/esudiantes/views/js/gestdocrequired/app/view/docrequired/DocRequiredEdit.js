Ext.define('GestDocRequired.view.docrequired.DocRequiredEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.docrequirededit',
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
                        name: 'iddocumentorequerido'
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: perfil.etiquetas.lbHdrDescripcion,
                        name: 'descripcion',
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