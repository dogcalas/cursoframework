Ext.define('GestConv.store.Annos', {
    extend: 'Ext.data.Store',
    alias:'widget.anno_combo',
    model: 'GestConv.model.Annos',

    autoLoad: true,
    listeners: {
        load: function () {
            var hoy1 = new Date();
            Ext.getCmp('anno').select(hoy1.getFullYear().toString());
        }
    },
    proxy: {
        type: 'ajax',
        api: {
            read: 'cargarAnnos'
        },
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos'
        }
    }
});