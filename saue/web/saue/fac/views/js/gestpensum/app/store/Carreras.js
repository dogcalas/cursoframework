Ext.define('GestPensums.store.Carreras', {
    extend: 'Ext.data.Store',
    model: 'GestPensums.model.Carrera',

    pageSize: 24,
    autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'cargarCarreras',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});