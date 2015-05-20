Ext.define('GestDiscapacidad.model.Discapacidad', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'iddiscapacidad', type: 'int', convert: null},
        {name: 'discapacidad', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})