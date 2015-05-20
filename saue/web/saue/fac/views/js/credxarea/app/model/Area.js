Ext.define('CredxArea.model.Area', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idarea', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'}
    ]
})