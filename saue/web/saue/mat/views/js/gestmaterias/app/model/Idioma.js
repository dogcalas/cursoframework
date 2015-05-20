Ext.define('GestMaterias.model.Idioma', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'ididioma', type: 'int', convert: null},
        {name: 'nivel', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'}
    ]
})