Ext.define('GestDocRequired.model.DocRequired', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'iddocumentorequerido', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})