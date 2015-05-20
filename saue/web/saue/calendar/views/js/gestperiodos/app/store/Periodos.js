Ext.define('GestPeriodos.store.Periodos', {
    extend: 'Ext.data.Store',
    model: 'GestPeriodos.model.Periodos',

    autoLoad: true,
    pageSize: 24,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarPeriodos',
            create: 'insertarPeriodo',
            update: 'modificarPeriodo',
            destroy: 'eliminarPeriodo'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});