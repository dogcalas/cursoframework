Ext.define('GestMatxPensum.store.Materias', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.Materia',

    pageSize: 25,
    proxy: {
        type: 'rest',
        api: {
            read: 'cargarMaterias'
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