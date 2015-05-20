Ext.define('GestEmpresas.store.stEmpresas', {
    extend: 'Ext.data.Store',

    requires: [
        'GestEmpresas.model.empresa'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            autoLoad: true,
            storeId: 'MyStore',
            model: 'GestEmpresas.model.empresa',
            proxy: {
                type: 'ajax',
                api: {
                    read: 'cargarEmpresas', 
                    update: 'modificarEmpresa', 
                    create: 'insertarEmpresa', 
                    destroy: 'eliminarEmpresa'
                    },
                    actionMethods: {
                        read: 'POST', 
                        update: 'POST', 
                        create: 'POST', 
                        destroy: 'POST' 
                    },
                reader: {
                    type: 'json',
                    root: 'datos',
                    totalProperty: 'cantidad'
                }
            }
        }, cfg)]);
    }
});