Ext.define('GestNotas.store.Cursos', {
    extend: 'Ext.data.Store',
    model: 'GestNotas.model.Cursos',

    //autoLoad: false,
    storeId: 'idStoreCursos',
    pageSize: 25,
    remoteFilter: true,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarCursos'
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