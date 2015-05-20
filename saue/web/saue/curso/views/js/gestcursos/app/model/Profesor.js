Ext.define('GestCursos.model.Profesor', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idprofesor', type: 'int', convert: null},
        {name: 'nombre',  type: 'string'},
        {name: 'apellidos',  type: 'string'}
    ]
})