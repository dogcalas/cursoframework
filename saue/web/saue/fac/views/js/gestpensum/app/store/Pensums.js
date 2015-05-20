Ext.define('GestPensums.store.Pensums', {
    extend: 'Ext.data.Store',
    model: 'GestPensums.model.Pensum',

    storeId: 'idStorePensum',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarPensum',
            create: 'insertarPensum',
            update: 'modificarPensum',
            destroy: 'eliminarPensum'
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