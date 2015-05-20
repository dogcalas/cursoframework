Ext.define('GestCursos.store.AnnoE', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.AnnoE',

    storeId: 'idCursosStoreAnnoE',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarAnnosE',
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
