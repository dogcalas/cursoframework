Ext.define('GestMatxPensum.store.Carreras', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.Carrera',

    //autoLoad: true,
    pageSize: 25,
    listeners: {
        load: function () {
            if (this.count() > 0) {
                Ext.getCmp('idMatxPensumCarrerasCombo').select(this.getAt(0).data.idcarrera);
            }
        }
    },
    proxy: {
        type: 'rest',
        url: 'cargarCarreras',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});