Ext.define('GestCarreras.model.Carrera', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idcarrera', type: 'int', convert: null},
        {name: 'idfacultad', type: 'int', convert: null},
        {name: 'descripcion_carrera', mapping: 'descripcion', type: 'string'},
        {name: 'facultad', type: 'string'},
        {name: 'estado', type: 'bool'}
    ]
})