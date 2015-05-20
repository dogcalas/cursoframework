Ext.define('GestComponentes.view.GridPanelServicios', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpanelservicios',
    title: perfil.etiquetas.lbTitServs,
    frame: true,
    iconCls: 'icon-grid',
    closable: true,
    closeAction: 'hide',
    store: 'GridServiciosStore',
    selModel:{mode:'MULTI'},
    columns: [
        {hidden: true, hideable: false, dataIndex: 'id'},
        {header: perfil.etiquetas.lbTitIdent, width: '20%', dataIndex: 'id', flex: 1},
        {header: perfil.etiquetas.lbTitInterfaz, width: '40%', dataIndex: 'interface'},
        {header: perfil.etiquetas.lbTitImplement, width: '40%', dataIndex: 'impl'},
    ],
    loadMask: {store: 'GridServiciosStore'},
    initComponent: function() {
        this.tbar = [
            {xtype: 'button', disabled: false,
                itemId: 'btnAddServ',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAdicionar, },
            {xtype: 'button', disabled: true,
                itemId: 'btnModServ',
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnModificar}, {
                xtype: 'button',
                disabled: true,
                itemId: 'btnDelServ',
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnEliminar
            }];


        this.callParent();
    }


});
