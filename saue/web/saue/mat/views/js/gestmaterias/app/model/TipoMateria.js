Ext.define('GestMaterias.model.TipoMateria', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipomateria', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'}
    ]
})