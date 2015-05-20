Ext.define('App.model.GDisc', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idgradodiscapacidad', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})
