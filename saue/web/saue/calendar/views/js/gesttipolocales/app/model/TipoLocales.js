Ext.define('GestTipoLocales.model.TipoLocales', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipo_aula', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})