Ext.define('GestTipoEstudiante.store.TipoEstudiante', {
    extend: 'Ext.data.Store',
    model: 'GestTipoEstudiante.model.TipoEstudiante',

    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTipoEstudiantes',
            create: 'insertarTipoEstudiante',
            update: 'modificarTipoEstudiante',
            destroy: 'eliminarTipoEstudiante'
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