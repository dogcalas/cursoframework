Ext.define('GestMateriaxMencion.store.Materias', {
    extend: 'Ext.data.Store',
    model: 'GestMateriaxMencion.model.Materia',

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