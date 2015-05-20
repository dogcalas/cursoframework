Ext.define('GestEtnias.model.Etnia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idetnia', type: 'int', convert: null},
        {name: 'etnia', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})