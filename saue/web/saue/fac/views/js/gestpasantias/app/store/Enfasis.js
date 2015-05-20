Ext.define('GestPasantias.store.Enfasis', {
    extend: 'Ext.data.Store',
    model: 'GestPasantias.model.Enfasi',

    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarEnfasis',
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