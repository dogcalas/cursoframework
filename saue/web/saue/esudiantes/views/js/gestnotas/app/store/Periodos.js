Ext.define('GestNotas.store.Periodos', {
    extend: 'Ext.data.Store',
    model: 'GestNotas.model.Periodos',
    //autoLoad: false,
    storeId: 'idStorePeriodos',
    proxy: {
        type: 'ajax',
        api: {
            read: '../gestnotas/cargarPeriodos'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});