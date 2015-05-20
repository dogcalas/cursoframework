Ext.define('GestFaltas.model.FaltasDias', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'id_falta_log', type: 'int'},
        {name: 'idalumno', type: 'int'},
        {name: 'idpd', type: 'int'},
        {name: 'idmateria', type: 'int'},
        {name: 'codigo', type: 'string'},
        {name: 'des_horario', type: 'descripcion'},
        {name: 'facultad', type: 'string'},
        {name: 'codmateria', type: 'string'},
        {name: 'materia', type: 'string'},
        {name: 'nombre', type: 'string'},
        {name: 'apellidos', type: 'string'},
        {name: 'semanas',  type: 'int'},
        {name: 'par_curso',  type: 'int'},
        {name: 'diasemana',  type: 'string'},
        {name: 'fecha',  type: 'string'},
        {name: 'val_falta',  type: 'int'},
        {name: 'falta_ini',  type: 'int'},
        {name: 'usuario',  type: 'string'},
        {name: 'observaciones',  type: 'string'}
    ]
});