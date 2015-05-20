Ext.define('GestComponentes.view.WinModEventO', {
    extend: 'Ext.Window',
    alias: 'widget.winmodevento',
    closeAction: 'destroy',
    layout: 'border',
    resizable: false,
    title: perfil.etiquetas.lbTitModObs,
    width: 500,
    height: 350,
    initComponent: function() {
        this.items = [{xtype: 'panelarboleventobs'}, {xtype: 'formaddevento'}];
        this.buttons = [{xtype: 'button',
                itemId: 'btncancModEventO',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar},
            {xtype: 'button',
                itemId: 'btnacepModEventO',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar}];

        this.callParent();
    },
});
