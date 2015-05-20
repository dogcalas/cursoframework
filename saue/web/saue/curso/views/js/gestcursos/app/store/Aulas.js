Ext.define('GestCursos.store.Aulas', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Aula',

    storeId: 'idCursosStoreAulas',
    //autoLoad: true,
    //pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarAulas',
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