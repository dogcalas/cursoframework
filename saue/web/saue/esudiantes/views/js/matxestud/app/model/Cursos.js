Ext.define('MatEst.model.Cursos', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idcurso', type: 'int'},
        {name: 'idmateria', type: 'int'},
        {name: 'idaula', type: 'int'},
        {name: 'idhorario', type: 'int'},
        {name: 'idprofesor', type: 'int'},
        {name: 'idperiododocente', type: 'int'},
        {name: 'cupo', type: 'int', convert: function(valor, record){
            return record.get('numalumnos') + '/' + valor;
        }},
        {name: 'par_curs', type: 'int'},
        {name: 'codmateria', type: 'string'},
        {name: 'materia', mapping: 'materia_descripcion', type: 'string'},
        {name: 'aula', mapping: 'aula_descripcion', type: 'string'},
        {name: 'profesor_nombre', type: 'string'},
        {name: 'profesor_apellidos', type: 'string'},
        {name: 'profesor', type: 'string', convert: function(valor, record){
            return record.get('profesor_nombre') + ' ' + record.get('profesor_apellidos') ;
        }},
        {name: 'horario', mapping: 'horario_descripcion', type: 'string'},
       // {name: 'horario', mapping: 'horario_descripcion', type: 'string'},
        {name: 'numalumnos', mapping: 'n_alumnos',  type: 'string'}
    ]
})