Ext.define('GestPreRequisitos.view.prerequisito.ToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.prerequisitomateriaparaprerequisitolisttbar',

    initComponent: function () {

        this.items = [
            {
                xtype: 'searchcombofield',
                store: 'GestPreRequisitos.store.TipoMaterias',
                emptyText: 'Filtrar por tipo de materia',
                name: 'idtipomateria',
                valueField: 'idtipomateria',
                displayField: 'descripcion',
                storeToFilter: 'GestPreRequisitos.store.MateriasParaPreRequisitos',//Ext.data.StoreManager.lookup('idStoreMateriasParaPreRequisito'),
                //filterPropertysNames: ['idtipomateria'],
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: 'GestPreRequisitos.store.MateriasParaPreRequisitos',//Ext.data.StoreManager.lookup('idStoreMateriasParaPreRequisito'),
                emptyText: 'Filtrar por código o descripción',
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40,
                filterPropertysNames: ['codmateria', 'descripcion']
            }
        ];

        this.callParent(arguments);
    }
});