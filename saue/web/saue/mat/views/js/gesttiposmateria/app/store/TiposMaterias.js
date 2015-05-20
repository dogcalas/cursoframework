Ext.define('GestTiposMateria.store.TiposMaterias', {
    extend: 'Ext.data.Store',
    model: 'GestTiposMateria.model.TipoMateria',//

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTiposMateria',
            create: 'insertarTiposMateria',
            update: 'modificarTiposMateria',
            destroy: 'eliminarTiposMateria'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            cantProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});