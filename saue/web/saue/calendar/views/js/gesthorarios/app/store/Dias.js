Ext.define('GestHorarios.store.Dias', {
    extend: 'Ext.data.Store',
    model: 'GestHorarios.model.Dias',

    autoLoad: true,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarDiasSemana'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});