Ext.define('GestLocales.model.Ubicaciones', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idcampus', type: 'int', convert: null},
        {name: 'descripcion', type: 'string', convert: null},
        {name: 'abrev', type: 'string'}
    ]
})