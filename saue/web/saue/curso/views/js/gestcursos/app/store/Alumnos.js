Ext.define('GestCursos.store.Alumnos', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Alumno',
    storeId: 'idCursosStoreAlumnos',
    id: 'idCursosStoreAlumnos',
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarAlumnos',
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