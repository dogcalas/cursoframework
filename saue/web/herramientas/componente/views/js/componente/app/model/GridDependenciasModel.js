Ext.define('GestComponentes.model.GridDependenciasModel', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'string'},
        {name: 'interface', type: 'string'},
        {name: 'use', type: 'string'},
        {name: 'optional', type: 'string'},
    ],
    proxy: {
        type: 'ajax',
        url: 'cargarDependencias',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
