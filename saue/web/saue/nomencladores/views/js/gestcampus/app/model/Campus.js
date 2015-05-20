Ext.define('App.model.Campus', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idcampus', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'abrev', mapping: 'abrev', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})
