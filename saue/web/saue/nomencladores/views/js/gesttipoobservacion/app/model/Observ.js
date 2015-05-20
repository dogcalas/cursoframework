Ext.define('App.model.Observ', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipoobservacion', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})
