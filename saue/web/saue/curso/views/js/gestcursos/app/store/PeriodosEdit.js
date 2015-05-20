Ext.define('GestCursos.store.PeriodosEdit', {
    extend: 'Ext.data.Store',
    model: 'GestCursos.model.Periodo',

    storeId: 'idCursosStorePeriodosEdit',

    //pageSize: 25,
    listeners: {
        load: function () {
            if (this.count() > 0 && Ext.getCmp('idperiododocentetb').getValue() == null) {
                Ext.getCmp('periodo_descripcion_edit').select(this.getAt(0).data.idperiododocente);
            } else {
                var pos = this.findExact('idperiododocente', Ext.getCmp('idperiododocentetb').getValue());
                Ext.getCmp('periodo_descripcion_edit').select(this.getAt(pos).data.idperiododocente);
            }
        }
    },
    proxy: {
        type: 'rest',
        url: 'cargarPeriodosEdit',
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
