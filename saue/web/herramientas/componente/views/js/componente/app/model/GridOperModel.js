Ext.define('GestComponentes.model.GridOperModel', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'nombre', type: 'string'},
        {name: 'retorno', type: 'string'},
        {name: 'parametros'},
        {name: 'descripoper', type: 'string'},
        {name: 'cantparm', type: 'int'},
    ],
});
