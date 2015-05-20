Ext.define('Sancion.store.stSancion', {
    extend: 'Ext.data.Store',

    requires: [
        'Sancion.model.Sancion'
    ],
    proxy: {
            type: 'ajax',
            api: {
                read: 'cargarSanciones',
                create: 'insertarSancion',
                update: 'modificarSancion',
                destroy: 'eliminarSancion'
            },
            actionMethods: {
                read: 'POST',
                update: 'POST'
            },
            reader: {
                type: 'json',
                root: 'datos',
                totalProperty: 'cantidad',
                successProperty: 'success',
            }
        },
    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            autoLoad: true,
            storeId: 'stSancion',
            model: 'Sancion.model.Sancion',
        }, cfg)]);
    }
});