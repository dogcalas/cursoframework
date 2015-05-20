Ext.define('GestTipoPeriodo.store.TipoPeriodo', {
    extend: 'Ext.data.Store',
    model: 'GestTipoPeriodo.model.TipoPeriodo',
    id: 'sttipoperiodo',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTipoPeriodos',
            create: 'insertarTipoPeriodo',
            update: 'modificarTipoPeriodo',
            destroy: 'eliminarTipoPeriodo'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            cantProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});
