Ext.define('GestCoRequisitos.store.Materias', {
        extend: 'Ext.data.Store',
        model: 'GestCoRequisitos.model.Materia',

        autoLoad: 'true',
        storeId: 'idStoreCoRequisitoMateria',
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