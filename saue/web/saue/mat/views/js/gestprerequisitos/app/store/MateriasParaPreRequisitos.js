Ext.define('GestPreRequisitos.store.MateriasParaPreRequisitos', {
    extend: 'Ext.data.Store',
    model: 'GestPreRequisitos.model.MateriasParaPreRequisito',

    //autoLoad: true,
    pageSize: 20,
    storeId: 'idStoreMateriasParaPreRequisito',
    proxy: {
        type: 'rest',
        api: {
            read: 'cargarMateriasParaPreRequisitos'
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