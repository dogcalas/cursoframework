Ext.define('GestFaltas.view.faltas.FaltaList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.faltalist',
    id: 'faltalist',
    store: 'GestFaltas.store.Faltas',
    height: '100%',
    columns: [],
    selType: 'cellmodel',

    plugins: [
        Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 1,
            pluginId: 'cellplugin'
        })
    ],
    initComponent: function () {
        var me = this;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.callParent(arguments);
    }

});