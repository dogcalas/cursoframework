Ext.define('App.store.stCampus', {
    extend: 'Ext.data.Store',
    model: 'App.model.Campus',
    id: 'stCampus',

    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarCampus',
            create: 'insertarCampus',
            update: 'modificarCampus',
            destroy: 'eliminarCampus'
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
