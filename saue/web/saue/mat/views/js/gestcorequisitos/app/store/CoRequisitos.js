Ext.define('GestCoRequisitos.store.CoRequisitos', {
    extend: 'Ext.data.Store',
    model: 'GestCoRequisitos.model.CoRequisito',

    //autoLoad: true,
    id: 'idStoreCoRequisito',
    pageSize: 25,
    proxy: {
        type: 'rest',
        api: {
            read: 'cargarCorequisitos',
            create: 'insertarCorequisitos',
            update: 'modificarCorequisitos',
            destroy: 'eliminarCorequisitos'
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