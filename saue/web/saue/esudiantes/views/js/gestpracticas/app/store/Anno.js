Ext.define('PracWin.store.Anno', {
    extend: 'Ext.data.Store',
    model: 'PracWin.model.Anno',

    storeId: 'idPracStoreAnno',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: '../gestnotas/cargarAnnos',
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
