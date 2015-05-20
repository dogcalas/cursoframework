Ext.define('PracWin.store.stPracticas', {
    extend: 'Ext.data.Store',
    requires: [
        'PracWin.model.Practica'
    ],
    proxy: {
            type: 'ajax',
            api: {
                read: 'cargarPracticas',
                create: 'insertarPractica',
                update: 'modificarPractica',
                destroy: 'eliminarPractica'
            },
            actionMethods: {
                read: 'POST',
                update: 'POST'
            },
            reader: {
                type: 'json',
                root: 'datos',
                totalProperty: 'cantidad',
                successProperty: 'success'
            }
        },
    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'stpractica',
            model: 'PracWin.model.Practica'
        }, cfg)]);
    }
});