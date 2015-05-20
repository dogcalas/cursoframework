Ext.define('GestTiposMateria.model.TipoMateria', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipomateria', type: 'int', convert: null},
        {name: 'descripcion_tipo_materia', mapping: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})