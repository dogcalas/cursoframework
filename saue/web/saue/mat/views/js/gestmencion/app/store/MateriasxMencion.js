Ext.define('GestMateriaxMencion.store.MateriasxMencion', {
    extend: 'Ext.data.Store',
    model: 'GestMateriaxMencion.model.MateriaxMencion',

    //autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        api: {
            read: 'cargarMatxMencion',
            create: 'insertarMatxMencion',
            //update: 'modificarMatxMencion',
            destroy: 'eliminarMatxMencion'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            create: 'POST',
            //update: 'POST',
            destroy: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});