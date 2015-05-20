Ext.define('RegMaterias.store.Annos', {
    extend: 'Ext.data.Store',
    model: 'GestNotas.model.Annos',
    alias:'widget.anno_comboM',

    autoLoad: true,
    storeId: 'idStoreAnnos',
    proxy: {
        type: 'ajax',
        api: {
            read: '../gestnotas/cargarAnnos'
        },
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos'
        }
    }
});