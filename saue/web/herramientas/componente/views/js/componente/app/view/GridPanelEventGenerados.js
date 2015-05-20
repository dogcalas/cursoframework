Ext.define('GestComponentes.view.GridPanelEventGenerados', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpaneleventgen',
    title: perfil.etiquetas.lbTitEventG,
    frame: true,
    closable: true,
    iconCls: 'icon-grid',
    store: 'GridEventGenStore',
    selModel:{mode:'MULTI'},
    columns: [
        {hidden: true, hideable: false, dataIndex: 'id'},
        {header: perfil.etiquetas.lbTitIdent, width: '53%', dataIndex: 'id'},
        {header: perfil.etiquetas.lbTitChBclase, width: '46%', dataIndex: 'class'},
    ],
    loadMask: {store: 'GridEventGenStore'},
    initComponent: function() {
        this.tbar = [
            {xtype: 'button', disabled: false,
                itemId: 'btnAddEventG',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAdicionar, },
            {xtype: 'button', disabled: true,
                itemId: 'btnModEventG',
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnModificar}, {
                xtype: 'button',
                disabled: true,
                itemId: 'btnDelEventG',
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnEliminar
            }];

        this.callParent();
    }


});
