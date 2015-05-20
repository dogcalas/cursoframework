Ext.define('GestNotas.view.nota.NotaList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.notalist',
    id: 'notalist',

    store: 'GestNotas.store.Notas',
    columns: [],
    height: '100%',
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
