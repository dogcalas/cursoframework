Ext.define('GestComponentes.model.ArbolComponentesModel', {
    extend: 'Ext.data.TreeModel',
    fields: ['text', 'dir','componente'],
    proxy: {
        type: 'ajax',
        url: 'cargarCarpetasApp	',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
