Ext.define('GestNotas.store.Alumnos', {
    extend: 'Ext.data.Store',
    model: 'GestNotas.model.Alumnos',

    autoLoad: false,
    storeId: 'idStoreAlumnos',
    pageSize: 25,
    remoteFilter: true,
    proxy: {
        type: 'ajax',
        api: {
            read: '../gestestudiantes/cargarAlumnos'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});