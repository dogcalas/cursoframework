Ext.define('GestLocales.store.TipoLocales', {
    extend: 'Ext.data.Store',
    model: 'GestLocales.model.TipoLocales',

    autoLoad: true,
    pageSize: 24,
    proxy: {
        type: 'rest',
        url: '../gesttipolocales/cargarTipoLocalesA',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});