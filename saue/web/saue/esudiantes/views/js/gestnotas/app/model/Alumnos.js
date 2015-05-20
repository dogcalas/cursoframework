Ext.define('GestNotas.model.Alumnos', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idalumno', type: 'int'},
        {name: 'idfacultad', mapping:'idestructura', type: 'int'},
        {name: 'idcarrera', type: 'int'},
        {name: 'idenfasis', type: 'int'},
        {name: 'cedula', type: 'string'},
        {name: 'codigo', type: 'string'},
        {name: 'nombre', type: 'string'},
        {name: 'apellidos', type: 'string'},
        {name: 'facultad', type: 'string'},
        {name: 'carrera', type: 'string'},
        {name: 'enfasis', type: 'string'},
        {name: 'idpensum', type: 'string'},
        {name: 'pensum', type: 'string'},
        {name: 'anno', type: 'int'}
    ]
});
