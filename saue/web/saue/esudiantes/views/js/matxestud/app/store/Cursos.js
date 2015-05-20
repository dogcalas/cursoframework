Ext.define('MatEst.store.Cursos', {
    extend: 'Ext.data.Store',
    model: 'MatEst.model.Cursos',

    autoLoad: false,
    storeId: 'idStoreCursos',
    pageSize: 22,
    remoteFilter: true,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarCCursos'
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