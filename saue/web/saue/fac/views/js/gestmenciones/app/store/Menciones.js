Ext.define('GestMenciones.store.Menciones', {
    extend: 'Ext.data.Store',
    model: 'GestMenciones.model.Mencion',

    storeId: 'idStoreMenciones',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarMencion',
            create: 'insertarMencion',
            update: 'modificarMencion',
            destroy: 'eliminarMencion'
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