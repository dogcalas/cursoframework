Ext.define('CredxArea.store.AreasGenerales', {
    extend: 'Ext.data.Store',
    model: 'CredxArea.model.AreaGeneral',

    autoLoad: true,
    pageSize: 20,
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