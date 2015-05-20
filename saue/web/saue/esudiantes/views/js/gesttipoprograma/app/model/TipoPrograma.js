Ext.define('GestTipoPrograma.model.TipoPrograma', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipoprograma', type: 'int', convert: null},
        {name: 'descripcion', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})