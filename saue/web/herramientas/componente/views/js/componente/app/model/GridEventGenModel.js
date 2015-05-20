Ext.define('GestComponentes.model.GridEventGenModel', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'string'},
        {name: 'class', type: 'string'},
    ],
    proxy: {
        type: 'ajax',
        url: 'cargarEventosGen',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
