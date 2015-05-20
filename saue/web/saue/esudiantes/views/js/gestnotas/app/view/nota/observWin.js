Ext.define('GestNotas.view.nota.observWin', {
    extend: 'Ext.window.Window',
    alias: 'widget.observwin',
    layout: 'fit',
    title: 'Observaciones',
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
                        xtype: 'textarea',
                        id:'obs',
                        fieldLabel: 'Observación',
                        name: 'observacion',
                        labelAlign: 'top',
                        allowBlank: false
                    }
                ]
            }
        ];

        this.buttons = [
            {
                id: 'idBtnCancelar1',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar,
                action: 'cancelar',
                scope: this,
                handler: this.close
            },
            {
                id: 'idBtnAceptar1',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
                action: 'aceptar'
            }
        ];

        this.callParent(arguments);
    }
});