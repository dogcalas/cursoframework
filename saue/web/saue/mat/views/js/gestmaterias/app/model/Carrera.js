Ext.define('GestMaterias.model.Carrera', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idcarrera', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'}
    ]
})