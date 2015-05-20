Ext.define('GestMatxPensum.store.Facultades', {
    extend: 'Ext.data.Store',
    model: 'GestMatxPensum.model.Facultad',

    autoLoad: true,
    pageSize: 5,
    listeners: {
        load: function () {
            if (this.count() > 0) {
                Ext.getCmp('facultad_combo').select(this.getAt(0).data.idfacultad);
            }
        }
    },
    proxy: {
        type: 'ajax',
        url: 'cargarFacultades',
        actionMethods: { //Esta Linea es necesaria para el metodo de llamada POST o GET
            read: 'POST'
        },
        reader: {
            root: 'datos',
            totalProperty: 'cantidad'
        }
    }
});