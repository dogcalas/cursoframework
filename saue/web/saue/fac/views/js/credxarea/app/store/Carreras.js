Ext.define('CredxArea.store.Carreras', {
    extend: 'Ext.data.Store',
    model: 'GestEnfasis.model.Carrera',

    //autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: '../gestenfasis/cargarCarreras',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});