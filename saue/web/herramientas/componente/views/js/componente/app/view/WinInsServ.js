Ext.define('GestComponentes.view.WinInsServ', {
    extend: 'Ext.Window',
    alias: 'widget.wininsserv',
    frame: true,
    closeAction: 'destroy',
    layout: 'border',
    title: perfil.etiquetas.lbTitAddServ,
    width: 800,
    height: 450,
    initComponent: function() {
        this.items = [{layout: 'vbox', items: [{xtype: 'formaddserv'}, {xtype: 'gridpaneloper'}], region: 'west'}, {xtype: 'formaddoper'}];
        this.buttons = [{xtype: 'button',
                itemId: 'btncancAddServ',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar

            },
            {
                xtype: 'button',
                itemId: 'btnapliAddServ',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
            },
            {xtype: 'button',
                itemId: 'btnacepAddServ',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
            }];

        this.callParent();
    }
});
