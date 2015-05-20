Ext.define('GestComponentes.model.GridEventObsModel', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'source', type: 'string'},
        {name: 'impl', type: 'String'},
    ],
    proxy: {
        type: 'ajax',
        url: 'cargarEventosObs',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
