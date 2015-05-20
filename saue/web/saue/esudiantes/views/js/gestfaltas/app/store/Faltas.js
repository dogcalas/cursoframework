Ext.define('GestFaltas.store.Faltas', {
    extend: 'Ext.data.Store',
    model: 'GestFaltas.model.Faltas',

    autoLoad: false,
    storeId: 'idStoreFaltas',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarFaltas',
            update: 'modificarFalta'
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