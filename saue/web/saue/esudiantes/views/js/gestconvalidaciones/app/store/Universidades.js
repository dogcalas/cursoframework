Ext.define('GestConv.store.Universidades', {
    extend: 'Ext.data.Store',
    model: 'GestConv.model.Universidad',

    autoLoad: true,
    storeId: 'idStoreUniversidad',
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarUniversidades',
        },
        actionMethods: {
            read: 'POST',
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});