Ext.define('GestComponentes.view.WinModComp', {
    extend: 'Ext.Window',
    alias: 'widget.winmodcomp',
    closeAction: 'destroy',
    layout: 'fit',
    resizable: false,
    title: perfil.etiquetas.lbTitModComp,
    width: 550,
    height: 270,
    initComponent: function() {
        this.buttons = [{xtype: 'button',
                itemId: 'btncancModComp',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar},
            {xtype: 'button',
                itemId: 'btnacepModComp',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar}];

        this.callParent();
    },
});
