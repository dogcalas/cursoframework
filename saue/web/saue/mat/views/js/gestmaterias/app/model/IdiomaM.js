Ext.define('GestMaterias.model.IdiomaM', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'ididiomam', mapping: 'ididioma', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'}
    ]
})