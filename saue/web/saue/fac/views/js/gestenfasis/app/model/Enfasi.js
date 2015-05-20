Ext.define('GestEnfasis.model.Enfasi', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idenfasis', type: 'int', convert: null},
        {name: 'idcarrera', type: 'int', convert: null},
        {name: 'idfacultad', type: 'int', mapping: 'DatCarrera.idfacultad', convert: null},
        {name: 'descripcion_enfasi', mapping: 'descripcion', type: 'string'},
        {name: 'descripcion_carrera', mapping: 'DatCarrera.descripcion', type: 'string', convert: null},
        {name: 'estado', type: 'boolean', default: true}
    ]
})