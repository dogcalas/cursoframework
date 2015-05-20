Ext.define('GestHorarios.store.HorariosDeta', {
    extend: 'Ext.data.Store',
    model: 'GestHorarios.model.HorariosDeta',

    //autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarHorariosDeta',
            create: 'insertarHorarioDeta',
            //update: 'modificarHorario',
            destroy: 'eliminarHorarioDeta'
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