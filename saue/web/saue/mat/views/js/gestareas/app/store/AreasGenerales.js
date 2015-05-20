Ext.define('GestAreas.store.AreasGenerales', {
    extend: 'Ext.data.Store',
    model: 'GestAreas.model.AreaGeneral',

    autoLoad: true,
    pageSize: 5,
    storeId: 'idStoreAreasGenerales',
    /*data:[
        {'idareag':1, 'descripcion_area_general': 'BASICAS'},
        {'idareag':2, 'descripcion_area_general': 'ESPECIALIZACION'},
        {'idareag':3, 'descripcion_area_general': 'OTROS REQUISITOS'}
    ]*/
 proxy: {
        type: 'rest',
        url: 'cargarAreasGenerales',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});