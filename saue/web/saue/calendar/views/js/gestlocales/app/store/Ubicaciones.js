Ext.define('GestLocales.store.Ubicaciones', {
    extend: 'Ext.data.Store',
    model: 'GestLocales.model.Ubicaciones',

    autoLoad: true,
    pageSize: 24,
    proxy: {
        type: 'rest',
        url: '../gestperiodos/cargarCampus',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});