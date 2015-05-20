Ext.define('GestCursos.store.Materias', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Materia',

    storeId: 'idCursosStoreMaterias',
    //autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'cargarMaterias',
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