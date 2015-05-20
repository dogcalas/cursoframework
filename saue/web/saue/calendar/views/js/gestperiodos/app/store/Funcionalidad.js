Ext.define('GestPeriodos.store.Funcionalidad', {
    extend: 'Ext.data.Store',
    model: 'GestPeriodos.model.Funcionalidad',

    //autoLoad: true,
    pageSize: 24,
    proxy: {
        type: 'rest',
        url: 'cargarFuncionalidadesXrol',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});
