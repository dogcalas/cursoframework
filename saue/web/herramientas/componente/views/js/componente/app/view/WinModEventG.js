Ext.define('GestComponentes.view.WinModEventG', {
    extend: 'Ext.Window',
    alias: 'widget.winmodeventg',
    closeAction: 'destroy',
    layout: 'border',
    resizable: false,
    title: perfil.etiquetas.lbTitModEvent,
    width: 330,
    height: 240,
    initComponent: function() {
        this.items = [{xtype: 'formaddeventg'}];
        this.buttons = [{xtype: 'button',
                itemId: 'btncancModEventG',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar

            },
            {xtype: 'button',
                itemId: 'btnacepModEventG',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
            }];

        this.callParent();
    },
});
