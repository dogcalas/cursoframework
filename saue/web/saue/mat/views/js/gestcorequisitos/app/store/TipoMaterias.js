Ext.define('GestCoRequisitos.store.TipoMaterias', {
    extend: 'Ext.data.Store',
    model: 'GestMaterias.model.TipoMateria',

    autoLoad: true,
    pageSize: 25,
    storeId: 'idStoreCoRequisitoTipoMaterias',
    proxy: {
        type: 'rest',
        url: '../gesttiposmateria/cargarTiposMateria',
        /*api: {
            read: 'cargarTipoMateria',
            create: 'insertarTipoMateria',
            update: 'modificarTipoMateria',
            destroy: 'eliminarTipoMateria'
        },*/
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});