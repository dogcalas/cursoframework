Ext.define('App.store.stGDisc', {
    extend: 'Ext.data.Store',
    model: 'App.model.GDisc',
    id: 'stGDisc',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarGDisc',
            create: 'insertarGDisc',
            update: 'modificarGDisc',
            destroy: 'eliminarGDisc'
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
