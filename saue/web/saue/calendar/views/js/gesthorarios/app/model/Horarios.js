Ext.define('GestHorarios.model.Horarios', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idhorario', type: 'int',  hidden: true, hideable: false},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'iddias', hidden: true, hideable: false},
        {name: 'idhorario_detallado', hidden: true, hideable: false},
        {name: 'hora_inicio', type: 'time'},
        {name: 'hora_fin', type: 'time'},
        {name: 'dsemana', type: 'string'},
        {name: 'frecuencia', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})