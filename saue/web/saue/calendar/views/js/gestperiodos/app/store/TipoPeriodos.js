Ext.define('GestPeriodos.store.TipoPeriodos', {
    extend: 'Ext.data.Store',
    model: 'GestPeriodos.model.TipoPeriodos',

    autoLoad: true,
    pageSize: 24,
    proxy: {
        type: 'rest',
        url: '../gesttipoperiodo/cargarTipoPeriodos',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});