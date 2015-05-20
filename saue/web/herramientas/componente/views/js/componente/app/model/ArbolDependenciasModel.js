Ext.define('GestComponentes.model.ArbolDependenciasModel', {
    extend: 'Ext.data.TreeModel',
    fields: ['text', 'dir', 'interface', 'version'],
    proxy: {
        type: 'ajax',
        url: 'cargarAllServicios',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
