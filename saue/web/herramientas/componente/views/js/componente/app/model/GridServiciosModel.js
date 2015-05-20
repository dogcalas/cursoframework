Ext.define('GestComponentes.model.GridServiciosModel', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'string'},
        {name: 'interface', type: 'string'},
        {name: 'impl', type: 'String'},
    ],
    proxy: {
        type: 'ajax',
        url: 'cargarServicios',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
