Ext.define('GestDiscapacidad.store.Discapacidad', {
    extend: 'Ext.data.Store',
    model: 'GestDiscapacidad.model.Discapacidad',

    autoLoad: true,
    pageSize: 24,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarDiscapacidadByD',
            create: 'insertarDiscapacidad',
            update: 'modificarDiscapacidad',
            destroy: 'eliminarDiscapacidad'
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