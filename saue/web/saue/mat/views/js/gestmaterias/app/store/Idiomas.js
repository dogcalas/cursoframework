Ext.define('GestMaterias.store.Idiomas', {
    extend: 'Ext.data.Store',
    model: 'GestMaterias.model.Idioma',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'rest',
        url: 'cargarIdiomas',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});