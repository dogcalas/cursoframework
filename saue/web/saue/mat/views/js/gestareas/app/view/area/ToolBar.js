Ext.define('GestAreas.view.area.ToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.arealisttbar',

    initComponent: function () {
        var me = this;

        me.items = [
            {
                itemId: 'idBtnAddArea',
                text: perfil.etiquetas.lbBtnAdicionar,
                icon: perfil.dirImg + 'adicionar.png',
                iconCls: 'btn',
                action: 'adicionar'
            },
            {
                itemId: 'idBtnUpdArea',
                disabled: true,
                text: perfil.etiquetas.lbBtnModificar,
                icon: perfil.dirImg + 'modificar.png',
                iconCls: 'btn',
                action: 'modificar'
            },
            {
                itemId: 'idBtnDelArea',
                disabled: true,
                text: perfil.etiquetas.lbBtnEliminar,
                icon: perfil.dirImg + 'eliminar.png',
                iconCls: 'btn',
                action: 'eliminar'
            },'->',
            {
                xtype: 'searchcombofield',
                store: Ext.data.StoreManager.lookup('idStoreAreasGenerales'),
                name: 'idareageneral',
                valueField: 'idareageneral',
                displayField: 'descripcion_area_general',
                storeToFilter: Ext.data.StoreManager.lookup('idStoreAreas'),
                //filterPropertysNames: ['idareageneral'],
                emptyText: 'Filtrar por nivel',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: Ext.data.StoreManager.lookup('idStoreAreas'),
                emptyText: 'Filtrar por descripción',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['descripcion']
            }
        ];

        this.callParent(arguments);
    }
});