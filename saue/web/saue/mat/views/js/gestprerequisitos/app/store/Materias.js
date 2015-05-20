Ext.define('GestPreRequisitos.store.Materias', {
        extend: 'Ext.data.Store',
        model: 'GestPreRequisitos.model.Materia',

        autoLoad: 'true',
        storeId: 'idStorePreRequisitoMateria',
        proxy: {
            type: 'rest',
            url: 'cargarMaterias',
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
    }
)