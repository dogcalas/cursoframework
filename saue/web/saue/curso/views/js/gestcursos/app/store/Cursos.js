Ext.define('GestCursos.store.Cursos', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Curso',

    storeId: 'idCursosStoreCursos',
    pageSize: 25,
    groupField: 'aula_descripcion',
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarCursos',
            create: 'insertarCursos',
            update: 'modificarCursos',
            destroy: 'eliminarCursos'
        },
        actionMethods: {
            read: 'POST',
            create: 'POST',
            update: 'POST',
            destroy: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            idProperty: 'idcurso',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});