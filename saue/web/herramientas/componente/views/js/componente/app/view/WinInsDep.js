Ext.define('GestComponentes.view.WinInsDep', {
    extend: 'Ext.Window',
    alias: 'widget.wininsdep',
    closeAction: 'destroy',
    layout: 'border',
    resizable: false,
    title: perfil.etiquetas.lbTitAddDep,
    width: 600,
    height: 300,
    initComponent: function() {
        this.items = [{xtype: 'panelarboldep'}, {xtype: 'formadddep'}];
        this.buttons = [{xtype: 'button',
                itemId: 'btncancAddDep',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar

            },
            {
                xtype: 'button',
                itemId: 'btnapliAddDep',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
            },
            {xtype: 'button',
                itemId: 'btnacepAddDep',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
            }];

        this.callParent();
    }
});
