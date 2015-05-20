Ext.define('GestMatxPensum.store.MateriasxPensum', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.MateriaxPensum',

    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarMatxPensum',
            create: 'insertarMatxPensum',
            update: 'modificarMatxPensum',
            destroy: 'eliminarMatxPensum'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            create: 'POST',
            update: 'POST',
            destroy: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});