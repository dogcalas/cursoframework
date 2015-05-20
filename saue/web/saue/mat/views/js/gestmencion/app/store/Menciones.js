Ext.define('GestMateriaxMencion.store.Menciones', {
    extend: 'Ext.data.Store',
    model: 'GestMateriaxMencion.model.Mencion',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: 'cargarMenciones',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});