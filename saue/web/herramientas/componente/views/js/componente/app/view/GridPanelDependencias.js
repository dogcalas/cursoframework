Ext.define('GestComponentes.view.GridPanelDependencias', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.gridpaneldependencias',
    title: perfil.etiquetas.lbTitDeps,
    frame: true,
    closable: true,
    iconCls: 'icon-grid',
    store: 'GridDependenciasStore',
    selModel:{mode:'MULTI'},
    columns: [
        {header: perfil.etiquetas.lbTitIdent, width: '30%', dataIndex: 'id', flex: 1},
        {header: perfil.etiquetas.lbTitInterfaz, width: '30%', dataIndex: 'interface'},
        {header: perfil.etiquetas.lbTitUso, width: '30%', dataIndex: 'use'},
        {header: perfil.etiquetas.lbTitOpc, width: '10%', dataIndex: 'optional'},
    ],
    loadMask: {store: 'GridDependenciasStore'},
    initComponent: function() {

        this.tbar = [
            {xtype: 'button', disabled: false,
                itemId: 'btnAddDep',
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnAdicionar, },
            {xtype: 'button', disabled: true,
                itemId: 'btnModDep',
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnModificar}, {
                xtype: 'button',
                disabled: true,
                itemId: 'btnDelDep',
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                text: perfil.etiquetas.lbBtnEliminar
            }];

        this.callParent();
    }


});
