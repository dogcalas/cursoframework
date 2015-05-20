Ext.define('GestCursos.store.Periodos', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Periodo',

    storeId: 'idCursosStorePeriodos',
    autoLoad: false,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarPeriodos',
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