Ext.define('PracWin.store.Periodo', {
    extend: 'Ext.data.Store',
    model: 'PracWin.model.Periodo',

    storeId: 'idPracStorePeriodosEdit',
    proxy: {
        type: 'rest',
        url: '../gestnotas/cargarPeriodos',
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

