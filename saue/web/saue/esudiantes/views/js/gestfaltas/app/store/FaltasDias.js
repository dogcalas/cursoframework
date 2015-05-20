Ext.define('GestFaltas.store.FaltasDias', {
    extend: 'Ext.data.Store',
    model: 'GestFaltas.model.FaltasDias',

    //autoLoad: false,
    storeId: 'idStoreFaltasDias',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarFaltasDias',
            update: 'modificarFaltaDias'
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