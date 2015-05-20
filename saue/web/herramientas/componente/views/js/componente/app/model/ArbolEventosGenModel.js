Ext.define('GestComponentes.model.ArbolEventosGenModel', {
    extend: 'Ext.data.TreeModel',
    fields: ['text', 'dir'],
    proxy: {
        type: 'ajax',
        url: 'cargarAllCompServ',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
