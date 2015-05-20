Ext.define('MatEst.store.Registro', {
    extend: 'Ext.data.Store',
    model: 'MatEst.model.Registro',

    autoLoad: false,
    storeId: 'idStoreRegistro',
    pageSize: 15,
    remoteFilter: true,
    proxy: {
        type: 'ajax',
        api: {
            read: 'loadRegistro'
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