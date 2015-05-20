Ext.define('GestComponentes.view.WinModServ', {
    extend: 'Ext.Window',
    alias: 'widget.winmodserv',
    frame: true,
    closeAction: 'destroy',
    layout: 'border',
    title: perfil.etiquetas.lbTitModServ,
    width: 800,
    height: 450,
    initComponent: function() {
        this.items = [{layout: 'vbox', items: [{xtype: 'formmodserv'}, {xtype: 'gridpaneloperm'}], region: 'west'}, {xtype: 'formmodoper'}];
        this.buttons = [{xtype: 'button',
                itemId: 'btncancModServ',
                icon: perfil.dirImg + 'cancelar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnCancelar},
            {xtype: 'button',
                itemId: 'btnacepModServ',
                icon: perfil.dirImg + 'aceptar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAceptar}];

        this.callParent();
    }
});
