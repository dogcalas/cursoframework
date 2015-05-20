Ext.define('GestHorarios.model.HorariosDeta', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idhorario_detallado', type: 'int', convert: null},
        {name: 'idhorario', type: 'int'},
        {name: 'iddias', type: 'int'},
        {name: 'descripcion', type: 'string'},
        {name: 'hora_inicio', type: 'time'},
        {name: 'hora_fin', type: 'time'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})