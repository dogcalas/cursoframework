Ext.define('GestMatxPensum.store.Enfasis', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.Enfasi',

    //autoLoad: true,
    pageSize: 25,
    listeners: {
        load: function () {
            if (this.count() > 0) {
                Ext.getCmp('idMatxPensumEnfasisCombo').select(this.getAt(0).data.idenfasis);
            }
        }
    },
    proxy: {
        type: 'rest',
        url: 'cargarEnfasis',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});