Ext.define('GestPeriodos.store.PeriodoTree', {
    extend: 'Ext.data.TreeStore',
    model: 'GestPeriodos.model.PeriodoTree',
    idProperty: 'id',
    //autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'cargarsistemafunc',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});
