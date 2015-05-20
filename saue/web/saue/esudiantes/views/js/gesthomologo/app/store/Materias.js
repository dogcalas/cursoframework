Ext.define('GestHom.store.Materias', {
    extend: 'Ext.data.Store',
    model: 'GestHom.model.Materia',

    autoLoad: false,
    storeId: 'idStoreMateriaH',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarMateriasHomologar',
            update: 'homologarMaterias',
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST',
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});