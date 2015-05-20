Ext.define('GestNotas.store.NotaLog', {
    extend: 'Ext.data.Store',
    model: 'GestNotas.model.NotaLog',

    autoLoad: false,
    storeId: 'idStoreNotaLog',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarNotasLog',
            update: 'modificarNotaDias'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});