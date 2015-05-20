Ext.define('GestCarreras.store.Carreras', {
    extend: 'Ext.data.Store',
    model: 'GestCarreras.model.Carrera',

    autoLoad: true,
    //autoSync: true,
    storeId: 'idStoreCarreras',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarCarreras',
            create: 'insertarCarreras',
            update: 'modificarCarreras',
            destroy: 'eliminarCarreras'
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