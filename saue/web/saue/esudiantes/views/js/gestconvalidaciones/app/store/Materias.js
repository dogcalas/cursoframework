Ext.define('GestConv.store.Materias', {
    extend: 'Ext.data.Store',
    model: 'GestConv.model.Materia',

    autoLoad: false,
    storeId: 'idStoreMateria',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarMateriasConvalidar',
            update: 'convalidarMaterias'
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