Ext.define('GestMaterias.store.Areas', {
    extend: 'Ext.data.Store',
    model: 'GestAreas.model.Area',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: '../gestareas/cargarAreas',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});