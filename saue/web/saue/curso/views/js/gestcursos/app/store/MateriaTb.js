Ext.define('GestCursos.store.MateriaTb', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.MateriaTb',

    storeId: 'idCursosStoreMateriaTb',
    //autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarMaterias',
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success'
        }
    }
});
