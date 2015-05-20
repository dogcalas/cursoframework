Ext.define('MatEst.model.Registro', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idcurso', type: 'int'},
        {name: 'idmateria', type: 'int'},
        {name: 'idaula', type: 'int'},
        {name: 'idhorario', type: 'int'},
        {name: 'idprofesor', type: 'int'},       
        {name: 'codmateria', mapping: 'codmateria', type: 'string'},
        {name: 'materia', mapping: 'materia_descripcion', type: 'string'},
        {name: 'aula', mapping: 'aula_descripcion', type: 'string'},
        {name: 'profesor_nombre', type: 'string'},
        {name: 'profesor_apellidos', type: 'string'},
        //{name: 'idcurso', mapping: 'idcurso', type: 'int'},
		
        {name: 'profesor', type: 'string', convert: function(valor, record){
            return record.get('profesor_nombre') + ' ' + record.get('profesor_apellidos') ;
        }},      
        {name: 'horario', mapping: 'horario_descripcion', type: 'string'},       
        {name: 'numalumnos', mapping: 'numalumnos',  type: 'string'}
    ]
})