Ext.define('GestHorarios.model.Dias', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'iddias', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'}
    ]
})