Ext.define('GestPreRequisitos.view.materia.ToolBar', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.prerequisitomaterialisttbar',

    initComponent: function () {

        this.items = [
            {
                xtype: 'searchcombofield',
                store: 'GestPreRequisitos.store.TipoMaterias',
                emptyText: 'Filtrar por tipo de materia',
                name: 'idtipomateria',
                valueField: 'idtipomateria',
                displayField: 'descripcion',
                storeToFilter: Ext.data.StoreManager.lookup('idStorePreRequisitoMateria'),
                //filterPropertysNames: ['idtipomateria'],
                width: 250,
                padding: '0 0 0 5',
                labelWidth: 40
            },
            {
                xtype: 'searchfield',
                store: Ext.data.StoreManager.lookup('idStorePreRequisitoMateria'),
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