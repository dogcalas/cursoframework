Ext.define('CredxArea.store.Pensums', {
    extend: 'Ext.data.Store',
    model: 'CredxArea.model.Pensum',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: 'cargarPensums',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});