Ext.define('GestEnfasis.store.Facultades', {
    extend: 'Ext.data.Store',
    model: 'GestCarreras.model.Facultad',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: '../gestcarreras/cargarFacultades',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});