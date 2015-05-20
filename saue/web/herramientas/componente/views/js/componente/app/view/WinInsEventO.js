Ext.define('GestComponentes.view.WinInsEventO', {
    extend: 'Ext.Window',
    alias: 'widget.wininsevento',
    closeAction: 'destroy',
    layout: 'border',
    resizable: false,
    title: perfil.etiquetas.lbTitAddObs,
    width: 500,
    height: 250,
    initComponent: function() {
        this.items = [{xtype: 'panelarboleventobs'}, {xtype: 'formaddevento'}];
        this.buttons = [{xtype: 'button',
                itemId: 'btncancAddEventO',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar

            },
            {
                xtype: 'button',
                itemId: 'btnapliAddEventO',
                icon: perfil.dirImg + 'aplicar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAplicar,
            },
            {xtype: 'button',
                itemId: 'btnacepAddEventO',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar,
            }];

        this.callParent();
    },
});
