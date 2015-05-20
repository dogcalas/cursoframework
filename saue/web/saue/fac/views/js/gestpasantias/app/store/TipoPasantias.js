Ext.define('GestPasantias.store.TipoPasantias', {
    extend: 'Ext.data.Store',
    model: 'GestPasantias.model.TipoPasantia',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: 'cargarTiposPasantias',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});