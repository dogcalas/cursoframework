Ext.define('App.store.stTDisc', {
    extend: 'Ext.data.Store',
    model: 'App.model.TDisc',
    id: 'stCampus',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTipoDisc',
            create: 'insertarTipoDisc',
            update: 'modificarTipoDisc',
            destroy: 'eliminarTipoDisc'
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
