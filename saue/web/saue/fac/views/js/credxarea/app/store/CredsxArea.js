Ext.define('CredxArea.store.CredsxArea', {
    extend: 'Ext.data.Store',
    model: 'CredxArea.model.CredxArea',

    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarCredsxArea',
            create: 'insertarCredsxArea',
            update: 'modificarCredsxArea',
            destroy: 'eliminarCredsxArea'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST',
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