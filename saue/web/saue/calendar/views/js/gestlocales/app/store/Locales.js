Ext.define('GestLocales.store.Locales', {
    extend: 'Ext.data.Store',
    model: 'GestLocales.model.Locales',

    autoLoad: true,
    pageSize: 24,
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarLocalesByD',
            create: 'insertarLocal',
            update: 'modificarLocal',
            destroy: 'eliminarLocal'
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