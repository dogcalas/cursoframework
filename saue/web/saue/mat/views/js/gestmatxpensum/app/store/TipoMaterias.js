Ext.define('GestMatxPensum.store.TipoMaterias', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.TipoMateria',

    autoLoad: true,
    pageSize: 5,
    /*data:[
        {'idtipomateria':1, 'descripcion': 'Tipo materia 1'},
        {'idtipomateria':2, 'descripcion': 'Tipo materia 2'},
        {'idtipomateria':3, 'descripcion': 'Tipo materia 3'},
        {'idtipomateria':4, 'descripcion': 'Tipo materia 4'},
        {'idtipomateria':5, 'descripcion': 'Tipo materia 5'},
        {'idtipomateria':6, 'descripcion': 'Tipo materia 6'}
    ]*/
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