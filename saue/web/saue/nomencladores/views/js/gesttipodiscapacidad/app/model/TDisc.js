Ext.define('App.model.TDisc', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipodiscapacidad', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})
