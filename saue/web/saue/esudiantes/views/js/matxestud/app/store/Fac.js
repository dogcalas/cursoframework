Ext.define('MatEst.store.Fac', {
    extend: 'Ext.data.Store',
    model: 'MatEst.model.Fac',
    autoLoad: true,
    storeId: 'idStoreFac',
    listeners: {
        load: function () {
            Ext.getCmp('idfacfiltro').select(this.first());
        }
    },

    proxy: {
        type: 'rest',
        api: {
            read: 'filtroFac'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            //totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});