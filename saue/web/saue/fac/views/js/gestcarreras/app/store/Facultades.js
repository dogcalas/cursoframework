Ext.define('GestCarreras.store.Facultades', {
    extend: 'Ext.data.Store',
    model: 'GestCarreras.model.Facultad',

    autoLoad: true,
    storeId: 'idStoreFacultades',
    pageSize: 20,
    /*data:[
        {'idfacultad':1, 'descripcion': 'Facultad 1'},
        {'idfacultad':2, 'descripcion': 'Facultad 2'},
        {'idfacultad':3, 'descripcion': 'Facultad 3'},
        {'idfacultad':4, 'descripcion': 'Facultad 4'},
        {'idfacultad':5, 'descripcion': 'Facultad 5'},
        {'idfacultad':6, 'descripcion': 'Facultad 6'}
    ]*/
    proxy: {
        type: 'rest',
        url: 'cargarFacultades',
        /*api: {
            read: 'cargarFacultades',
            create: 'insertarFacultades',
            update: 'modificarFacultades',
            destroy: 'eliminarFacultades'
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