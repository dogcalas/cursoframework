Ext.define('GestTipoEstudiante.model.TipoEstudiante', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipoalumno', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})