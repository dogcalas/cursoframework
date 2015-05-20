Ext.define('GestCoRequisitos.view.corequisito.ToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.corequisitomateriaparacorequisitolisttbar',

    initComponent: function () {

        this.items = [
            {
                xtype: 'searchcombofield',
                store: 'GestCoRequisitos.store.TipoMaterias',
                emptyText: 'Filtrar por tipo de materia',
                name: 'idtipomateria',
                valueField: 'idtipomateria',
                displayField: 'descripcion',
                storeToFilter: 'GestCoRequisitos.store.MateriasParaCoRequisitos',//Ext.data.StoreManager.lookup('idStoreMateriasParaCoRequisito'),
                //filterPropertysNames: ['idtipomateria'],
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: 'GestCoRequisitos.store.MateriasParaCoRequisitos',//Ext.data.StoreManager.lookup('idStoreMateriasParaCoRequisito'),
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