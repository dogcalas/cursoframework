Ext.define('GestTipoPrograma.store.TipoPrograma', {
    extend: 'Ext.data.Store',
    model: 'GestTipoPrograma.model.TipoPrograma',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTipoProgramas',
            create: 'insertarTipoPrograma',
            update: 'modificarTipoPrograma',
            destroy: 'eliminarTipoPrograma'
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