Ext.define('RegMaterias.store.PeriodoAdd', {
    extend: 'Ext.data.Store',
    model: 'RegMaterias.model.Periodos',
    autoLoad: false,
    storeId: 'idStorePeriodos',
    listeners: {
        load: function () {
            if (this.getCount() > 0) {
                Ext.getCmp('descripcion').select(this.first());
            }
        }
    },
    proxy: {
        type: 'ajax',
        api: {
            read: '../gestnotas/cargarPeriodos'
        },
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad',
            successProperty: 'success',
            messageProperty: 'mensaje'
        }
    }
});