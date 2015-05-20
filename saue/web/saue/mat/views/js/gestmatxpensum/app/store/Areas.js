Ext.define('GestMatxPensum.store.Areas', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.Area',

    //autoLoad: true,
    pageSize: 20,
    listeners: {
        load: function () {
            if (this.count() > 0) {
                Ext.getCmp('idarea').select(this.getAt(0).data.idarea);
            }
        }
    },
    proxy: {
        type: 'rest',
        url: 'cargarAreas',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});