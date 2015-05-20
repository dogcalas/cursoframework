Ext.define('GestLocales.model.Locales', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idaula', type: 'int', convert: null},
        {name: 'idtipo_aula', type: 'int', convert: null},
        {name: 'idcampus', type: 'int', convert: null},
        {name: 'aula', mapping: 'aula', type: 'string'},
        {name: 'local', mapping: 'local', type: 'string'},
        {name: 'abrev', mapping: 'abrev', type: 'string'},
        {name: 'capacidad', type: 'int', convert: null},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})