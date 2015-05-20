Ext.define('GestPreRequisitos.store.PreRequisitos', {
    extend: 'Ext.data.Store',
    model: 'GestPreRequisitos.model.PreRequisito',

    //autoLoad: true,
    id: 'idStorePreRequisito',
    pageSize: 25,
    proxy: {
        type: 'rest',
        api: {
            read: 'cargarPrerequisitos',
            create: 'insertarPrerequisitos',
            update: 'modificarPrerequisitos',
            destroy: 'eliminarPrerequisitos'
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