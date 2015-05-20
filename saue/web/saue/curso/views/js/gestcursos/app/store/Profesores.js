Ext.define('GestCursos.store.Profesores', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Profesor',

    storeId: 'idCursosStoreProfesores',
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarProfesores',
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success'
        }
    }
});