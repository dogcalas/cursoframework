Ext.define('GestComponentes.view.WinModDep', {
    extend: 'Ext.Window',
    alias: 'widget.winmoddep',
    closeAction: 'destroy',
    layout: 'border',
    resizable: false,
    title: perfil.etiquetas.lbTitModDep,
    width: 600,
    height: 350,
    initComponent: function() {
        this.items = [{xtype: 'panelarboldep'}, {xtype: 'formadddep'}];
        this.buttons = [{xtype: 'button',
                itemId: 'btncancAddDepM',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar

            },
            {xtype: 'button',
                itemId: 'btnacepAddDepM',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
            }];

        this.callParent();
    }
});
