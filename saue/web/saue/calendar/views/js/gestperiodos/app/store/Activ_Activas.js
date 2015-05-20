Ext.define('GestPeriodos.store.Activ_Activas', {
    extend: 'Ext.data.Store',
    model: 'GestPeriodos.model.Activ_Activas',

    pageSize: 11,
    groupField: 'rol',
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarActivActivas',
            update: 'modificarActivActivas',
            destroy: 'eliminarActivActivas'
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