Ext.define('GestPasantias.store.Pasantias', {
    extend: 'Ext.data.Store',
    model: 'GestPasantias.model.Pasantia',

    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarPasantias',
            create: 'insertarPasantias',
            update: 'modificarPasantias',
            destroy: 'eliminarPasantias'
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