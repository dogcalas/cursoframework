Ext.define('GestComponentes.view.WinInsEventG', {
    extend: 'Ext.Window',
    alias: 'widget.wininseventg',
    closeAction: 'destroy',
    layout: 'border',
    resizable: false,
    title: perfil.etiquetas.lbTitAddEvent,
    width: 330,
    height: 240,
    initComponent: function() {
        this.items = [{xtype: 'formaddeventg'}];
        this.buttons = [{xtype: 'button',
                itemId: 'btncancAddEventG',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar

            },
            {
                xtype: 'button',
                itemId: 'btnapliAddEventG',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
            },
            {xtype: 'button',
                itemId: 'btnacepAddEventG',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
            }];

        this.callParent();
    }
});
