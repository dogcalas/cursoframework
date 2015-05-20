Ext.define('GestCoRequisitos.view.materia.ToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.corequisitomaterialisttbar',

    initComponent: function () {

        this.items = [
            {
                xtype: 'searchcombofield',
                store: 'GestCoRequisitos.store.TipoMaterias',
                emptyText: 'Filtrar por tipo de materia',
                name: 'idtipomateria',
                valueField: 'idtipomateria',
                displayField: 'descripcion',
                storeToFilter: Ext.data.StoreManager.lookup('idStoreCoRequisitoMateria'),
                //filterPropertysNames: ['idtipomateria'],
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: Ext.data.StoreManager.lookup('idStoreCoRequisitoMateria'),
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