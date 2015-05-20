Ext.define('GestCursos.model.Horario', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idhorario', type: 'int', convert: null},
        {name: 'horario_descripcion', mapping: 'descripcion',  type: 'string'}
    ]
})