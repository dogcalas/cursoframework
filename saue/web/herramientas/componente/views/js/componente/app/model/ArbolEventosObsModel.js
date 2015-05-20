Ext.define('GestComponentes.model.ArbolEventosObsModel', {
    extend: 'Ext.data.TreeModel',
    fields: ['text', 'dir', 'impl'],
    proxy: {
        type: 'ajax',
        url: 'cargarAllEvent',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
