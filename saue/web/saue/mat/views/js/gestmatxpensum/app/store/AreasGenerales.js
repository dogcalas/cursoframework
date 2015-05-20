Ext.define('GestMatxPensum.store.AreasGenerales', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.AreaGeneral',

    //autoLoad: true,
    pageSize: 5,
    listeners: {
        load: function () {
            if (this.count() > 0) {
                Ext.getCmp('idareageneral').select(this.getAt(0).data.idareageneral);
            }
        }
    },
    proxy: {
        type: 'rest',
        url: 'cargarAreasGenerales',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});