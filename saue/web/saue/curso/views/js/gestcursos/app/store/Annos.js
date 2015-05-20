Ext.define('GestCursos.store.Annos', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Anno',

    storeId: 'idCursosStoreAnnos',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarAnnos',
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