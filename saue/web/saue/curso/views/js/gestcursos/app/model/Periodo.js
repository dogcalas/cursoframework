Ext.define('GestCursos.model.Periodo', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idperiododocente', type: 'int', convert: null},
        {name: 'descripcion',  type: 'string'}
    ]
})