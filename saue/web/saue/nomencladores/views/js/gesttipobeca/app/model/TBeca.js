Ext.define('App.model.TBeca', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipobeca', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'descuento', mapping: 'descuento', type: 'float'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})
