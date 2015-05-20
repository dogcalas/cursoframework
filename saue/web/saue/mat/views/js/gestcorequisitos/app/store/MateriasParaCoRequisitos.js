Ext.define('GestCoRequisitos.store.MateriasParaCoRequisitos', {
    extend: 'Ext.data.Store',
    model: 'GestCoRequisitos.model.MateriasParaCoRequisito',

    //autoLoad: true,
    pageSize: 20,
    storeId: 'idStoreMateriasParaCoRequisito',
    proxy: {
        type: 'rest',
        api: {
            read: 'cargarMateriasParaCoRequisitos'
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