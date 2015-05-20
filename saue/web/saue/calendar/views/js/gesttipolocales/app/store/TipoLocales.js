Ext.define('GestTipoLocales.store.TipoLocales', {
    extend: 'Ext.data.Store',
    model: 'GestTipoLocales.model.TipoLocales',

    autoLoad: true,
    pageSize: 20,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarTipoLocalesByD',
            create: 'insertarTipoLocal',
            update: 'modificarTipoLocal',
            destroy: 'eliminarTipoLocal'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST',
            update: 'POST'
        },
        reader: {
            root: 'datos',
            cantProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});