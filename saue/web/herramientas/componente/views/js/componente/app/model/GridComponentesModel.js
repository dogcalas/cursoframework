Ext.define('GestComponentes.model.GridComponentesModel', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'string'},
        {name: 'nombre', type: 'string'},
        {name: 'estado', type: 'String'},
        {name: 'version', type: 'string'},
        {name: 'direccion', type: 'string'},
        {name: 'servicios', type: 'string'},
        {name: 'dependencias', type: 'string'},
        {name: 'generados', type: 'string'},
        {name: 'observados', type: 'string'},
    ],
    proxy: {
        type: 'ajax',
        url: 'selArbol',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
