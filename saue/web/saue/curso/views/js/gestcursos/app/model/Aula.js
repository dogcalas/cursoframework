Ext.define('GestCursos.model.Aula', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idaula', type: 'int', convert: null},
        {name: 'capacidad', type: 'int'},
        {name: 'aula',  type: 'string'}
    ]
})