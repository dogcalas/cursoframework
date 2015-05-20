Ext.define('MatEst.store.Annos', {
    extend: 'Ext.data.Store',
    alias:'widget.anno_combo',
    model: 'GestNotas.model.Annos',

    autoLoad: true,
    storeId: 'idStoreAnnos',
    listeners: {
        load: function () {
            var hoy1 = new Date(),
                anno1 = hoy1.getFullYear().toString();
            Ext.getCmp('anno').select(anno1);
        }
    },
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarAnnosRegistro'
            //read: '../gestnotas/cargarAnnos'
        },
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos'
        }
    }
});
