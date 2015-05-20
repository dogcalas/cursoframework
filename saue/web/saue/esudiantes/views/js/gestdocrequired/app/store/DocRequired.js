Ext.define('GestDocRequired.store.DocRequired', {
    extend: 'Ext.data.Store',
    model: 'GestDocRequired.model.DocRequired',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarDocRequerido',
            create: 'insertarDocRequerido',
            update: 'modificarDocRequerido',
            destroy: 'eliminarDocRequerido'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            cantProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});