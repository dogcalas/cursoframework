Ext.define('GestComponentes.view.GridPanelEventObservados', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpaneleventobs',
    title: perfil.etiquetas.lbTitEventO,
    frame: true,
    closable: true,
    iconCls: 'icon-grid',
    store: 'GridEventObsStore',
    selModel:{mode:'MULTI'},
    columns: [
        {header: perfil.etiquetas.lbTitFormAddEventO, width: '50%', dataIndex: 'source'},
        {header: perfil.etiquetas.lbTitImplement, width: '50%', dataIndex: 'impl'},
    ],
    loadMask: {store: 'GridEventObsStore'},
    initComponent: function() {
        this.tbar = [
            {xtype: 'button', disabled: false,
                itemId: 'btnAddEventO',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAdicionar, },
            {xtype: 'button', disabled: true,
                itemId: 'btnModEventO',
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnModificar}, {
                xtype: 'button',
                disabled: true,
                itemId: 'btnDelEventO',
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnEliminar
            }];

        this.callParent();
    }


});
