Ext.define('App.store.stObserv', {
    extend: 'Ext.data.Store',
    model: 'App.model.Observ',
    id: 'stObserv',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTipoObserv',
            create: 'insertarTipoObserv',
            update: 'modificarTipoObserv',
            destroy: 'eliminarTipoObserv'
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
