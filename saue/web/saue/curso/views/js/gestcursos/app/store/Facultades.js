Ext.define('GestCursos.store.Facultades', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Facultad',

    storeId: 'idStoreCursosFacultades',
    autoLoad: true,
    //pageSize: 20,
    proxy: {
        type: 'rest',
        url: 'cargarFacultades',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});