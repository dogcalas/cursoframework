Ext.define('GestCursos.model.Curso', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idcurso', type: 'int', convert: null},
        {name: 'par_curs', type: 'int'},
        {name: 'cupo', type: 'int'},
        {name: 'n_alumnos', type: 'int'},
        {name: 'estado', type: 'boolean', default: true},
        {name: 'idprofesor', type: 'int', convert: null},
        {name: 'idperiododocente', type: 'int', convert: null},
        {name: 'periodo_descripcion', type: 'string'},
        {name: 'idfacultad', type: 'int', convert: null},
        {name: 'profesor_nombre', type: 'string', convert: null},
        {name: 'profesor_apellidos', type: 'string', convert: null},
        {
            name: 'profesor_nombre_completo',
            type: 'string',
            convert: function (value, record) {
                if(value)
                    return value;
                var nombre_completo = record.get('profesor_nombre') + ' ' + record.get('profesor_apellidos');
                return nombre_completo;
            }
        },
        {name: 'idhorario', type: 'int', convert: null},
        {name: 'horario_descripcion', type: 'string'},
        {name: 'idaula', type: 'int', convert: null},
        {name: 'aula_descripcion', type: 'string'},
        {name: 'idmateria', type: 'int', convert: null},
        {name: 'materia_descripcion', type: 'string'}
    ]
});