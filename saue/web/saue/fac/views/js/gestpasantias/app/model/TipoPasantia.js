Ext.define('GestPasantias.model.TipoPasantia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipopasantia', mapping: 'idtipopractica', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'}
    ]
})