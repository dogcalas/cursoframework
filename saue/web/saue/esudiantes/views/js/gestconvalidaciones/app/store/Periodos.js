Ext.define('GestConv.store.Periodos', {
    extend: 'Ext.data.Store',
    model: 'GestConv.model.Periodos',
    //autoLoad: false,
    storeId: 'idStorePeriodos',
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarPeriodos'
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