Ext.define('GestConv.store.MateriasConva', {
    extend: 'Ext.data.Store',
    model: 'GestConv.model.MateriasConva',

    autoLoad: false,
    storeId: 'idStoreMateriaConva',
    // pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarMateriasConva'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});