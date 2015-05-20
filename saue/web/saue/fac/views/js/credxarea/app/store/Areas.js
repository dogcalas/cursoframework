Ext.define('CredxArea.store.Areas', {
    extend: 'Ext.data.Store',
    model: 'CredxArea.model.Area',

    //autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: 'cargarAreas',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});