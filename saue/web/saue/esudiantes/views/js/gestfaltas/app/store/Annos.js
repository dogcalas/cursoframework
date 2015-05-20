Ext.define('GestFaltas.store.Annos', {
    extend: 'Ext.data.Store',
    model: 'GestFaltas.model.Annos',

    autoLoad: true,
    storeId: 'idStoreAnnos',
    listeners: {
        load: function () {
            var hoy2 = new Date(),
                anno2 = hoy2.getFullYear().toString();
            Ext.getCmp('anno').select(anno2);
        }
    },
    proxy: {
        type: 'ajax',
        api: {
            read: '../gestnotas/cargarAnnos'
        },
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos'
        }
    }
});