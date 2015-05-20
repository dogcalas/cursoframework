Ext.define('RegMaterias.store.Materia', {
    extend: 'Ext.data.Store',
    model: 'RegMaterias.model.Materia',

    autoLoad: false,
    storeId: 'idStoreMateriaAdd',
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: '../Cursos/cargarMateria'
        },
        actionMethods: {
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