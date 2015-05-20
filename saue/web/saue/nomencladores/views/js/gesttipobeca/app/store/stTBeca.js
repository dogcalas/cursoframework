Ext.define('App.store.stTBeca', {
    extend: 'Ext.data.Store',
    model: 'App.model.TBeca',
    id: 'stTBeca',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTBeca',
            create: 'insertarTBeca',
            update: 'modificarTBeca',
            destroy: 'eliminarTBeca'
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
