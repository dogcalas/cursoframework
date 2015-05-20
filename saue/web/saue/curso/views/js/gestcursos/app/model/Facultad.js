Ext.define('GestCursos.model.Facultad', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idfacultad', mapping: 'idestructura', type: 'int', convert: null},
        {name: 'denominacion', type: 'string'}
    ]
})