Ext.define('GestComponentes.model.GridOperMModel', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'nombre', type: 'string'},
        {name: 'retorno', type: 'string'},
        {name: 'parametros'},
        {name: 'descripoper', type: 'string'},
        {name: 'cantparm', type: 'int'},
    ],
    proxy: {
        type: 'ajax',
        actionMethods: {read: 'POST'},
        reader: {
            type: 'json',
            root: 'data'

        }

    }
});
