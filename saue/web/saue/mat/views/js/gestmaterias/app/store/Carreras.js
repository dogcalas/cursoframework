Ext.define('GestMaterias.store.Carreras', {
    extend: 'Ext.data.Store',
    model: 'GestMaterias.model.Carrera',

    autoLoad: true,
    pageSize: 20,
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