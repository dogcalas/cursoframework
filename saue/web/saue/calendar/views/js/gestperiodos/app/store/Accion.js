Ext.define('GestPeriodos.store.Accion', {
    extend: 'Ext.data.Store',
    model: 'GestPeriodos.model.Accion',
    alias: 'widget.accionstore',
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarAccionesXfuncionalidad',
            create: 'restAcceso'
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