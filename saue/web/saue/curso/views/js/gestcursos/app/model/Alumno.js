Ext.define('GestCursos.model.Alumno', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idalumno', type: 'int', convert: null},
        {name: 'codigo', type: 'int', mapping: 'codigo', convert: null},
        {name: 'nombre', mapping: 'nombre', type: 'string'},
        {name: 'apellidos', mapping: 'apellidos', type: 'string'},
        {
            name: 'nombre_completo',
            type: 'string',
            convert: function (value, record) {
                if (value)
                    return value;
                return record.get('apellidos') + ' ' + record.get('nombre');
                //return nombre_completo;
            }
        },
        {name: 'facultad',  type: 'string'},
        {name: 'nota',  type: 'int'},
        {name: 'falta', type: 'int'},
        {name: 'observacion', type: 'string'},
        {name: 'e_mail', type: 'string'},
        {name: 'tipoaprobado', type: 'string'}
    ]
});