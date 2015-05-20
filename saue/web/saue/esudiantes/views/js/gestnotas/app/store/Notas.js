Ext.define('GestNotas.store.Notas', {
    extend: 'Ext.data.Store',
    model: 'GestNotas.model.Notas',

    autoLoad: false,
    storeId: 'idStoreNotas',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarNotas',
            update: 'modificarNota'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});