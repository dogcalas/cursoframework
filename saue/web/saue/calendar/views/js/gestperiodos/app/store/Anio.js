Ext.define('GestPeriodos.store.Anio', {
    extend: 'Ext.data.Store',
    model: 'GestPeriodos.model.Anio',

    autoLoad: true,
    pageSize: 24,
    listeners: {
        load: function () {
            var hoyA = new Date(),
                annoA = hoyA.getFullYear().toString();
            Ext.getCmp('idPeriodoAnioCombo').select(annoA);
        }
    },
    proxy: {
        type: 'rest',
        url: 'cargarAnnos',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});
