Ext.define('MatEst.store.Horarios', {
    extend: 'Ext.data.Store',
    model: 'MatEst.model.Horario',

    storeId: 'idCursosStoreHorarios',
    /*listeners: {
        load: function () {
            if (this.getCount() > 0) {
                Ext.getCmp('idhorariofiltro').select(this.first());
            }
        }
    },*/
    proxy: {
        type: 'rest',
        url: 'cargarHorarios',
        actionMethods: {
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success'
        }
    }
});