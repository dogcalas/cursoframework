Ext.define('CredxArea.store.Enfasis', {
    extend: 'Ext.data.Store',
    model: 'CredxArea.model.Enfasi',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: 'cargarEnfasis',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});