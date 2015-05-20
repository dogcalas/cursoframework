Ext.define('GestHom.store.MateriasLibre', {
    extend: 'Ext.data.Store',
    model: 'GestHom.model.MateriasLibre',

    autoLoad: false,
    storeId: 'idStoreMateriasLibre',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarMateriasLibres'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});