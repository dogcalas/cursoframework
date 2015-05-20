Ext.define('GestEnfasis.store.Enfasis', {
    extend: 'Ext.data.Store',
    model: 'GestEnfasis.model.Enfasi',

    autoLoad: true,
    storeId: 'idStoreEnfasis',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarEnfasis',
            create: 'insertarEnfasis',
            update: 'modificarEnfasis',
            destroy: 'eliminarEnfasis'
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