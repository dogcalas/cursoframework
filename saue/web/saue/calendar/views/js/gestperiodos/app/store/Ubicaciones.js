Ext.define('GestPeriodos.store.Ubicaciones', {
    extend: 'Ext.data.Store',
    model: 'GestPeriodos.model.Ubicaciones',

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