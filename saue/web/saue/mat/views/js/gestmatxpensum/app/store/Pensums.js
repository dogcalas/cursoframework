Ext.define('GestMatxPensum.store.Pensums', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.Pensum',

    //autoLoad: true,
    pageSize: 25,
    listeners: {
        load: function () {
            if (this.count() > 0) {
                Ext.getCmp('idpensum').select(this.getAt(0).data.idpensum);
            }
        }
    },
    proxy: {
        type: 'rest',
        url: 'cargarPensum',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});