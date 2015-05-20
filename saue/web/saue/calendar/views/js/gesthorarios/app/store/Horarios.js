Ext.define('GestHorarios.store.Horarios', {
    extend: 'Ext.data.Store',
    model: 'GestHorarios.model.Horarios',

    autoLoad: true,
    pageSize: 25,
    //groupField: 'descripcion',
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarHorarios',
            create: 'insertarHorario',
            update: 'modificarHorario',
            destroy: 'eliminarHorario'
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