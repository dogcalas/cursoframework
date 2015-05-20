
Ext.define('PracWin.store.stSuma', {
    extend: 'Ext.data.Store',

    requires: [
        'PracWin.model.SumaPasantias'
    ], 
    proxy: {
         type: 'ajax',
         url: 'cargarTpasantias',
         reader: {
             type: 'json',
             root: 'datos'
         }
     },
    autoLoad: true,
    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'sumStore',
            model: 'PracWin.model.SumaPasantias'
        }, cfg)]);
    }
});