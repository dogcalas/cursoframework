Ext.define('GestPensums.model.Pensum', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idpensum', type: 'int', convert: null},
        {name: 'idcarrera', type: 'int', convert: null},
        {name: 'idfacultad', type: 'int', mapping: 'DatCarrera.idfacultad', convert: null},
        {name: 'descripcion_carrera', mapping: 'DatCarrera.descripcion', type: 'string', convert: null},
        {name: 'descripcion_pensum', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'bool'}
    ]
})