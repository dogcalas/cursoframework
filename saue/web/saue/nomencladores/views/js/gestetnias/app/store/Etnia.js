Ext.define('GestEtnias.store.Etnia', {
    extend: 'Ext.data.Store',
    model: 'GestEtnias.model.Etnia',

    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarEtniaByD',
            create: 'insertarEtnia',
            update: 'modificarEtnia',
            destroy: 'eliminarEtnia'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});