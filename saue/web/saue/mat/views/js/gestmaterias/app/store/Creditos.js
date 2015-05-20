Ext.define('GestMaterias.store.Creditos', {
    extend: 'Ext.data.Store',
    model: 'GestMaterias.model.Credito',

    //autoLoad: true,
    pageSize: 25,
    //autoSync: true,
    proxy: {
        type: 'rest',
        api: {
            read: 'cargarCreditos',
            //create: 'insertarCreditos',
            update: 'gestionarCreditos'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});