Ext.define('GestPasantias.store.Carreras', {
    extend: 'Ext.data.Store',
    model: 'GestEnfasis.model.Carrera',

    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: '../gestenfasis/cargarCarreras',
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