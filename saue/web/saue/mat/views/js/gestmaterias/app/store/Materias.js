Ext.define('GestMaterias.store.Materias', {
    extend: 'Ext.data.Store',
    model: 'GestMaterias.model.Materia',

    autoLoad: true,
    storeId: 'idStoreMaterias',
    pageSize: 25,
    batchUpdateMode: 'complete',
    proxy: {
        type: 'rest',
        api: {
            read: 'cargarMaterias',
            create: 'insertarMaterias',
            update: 'modificarMaterias',
            destroy: 'eliminarMaterias'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});