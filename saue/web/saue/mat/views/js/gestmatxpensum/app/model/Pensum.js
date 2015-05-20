Ext.define('GestMatxPensum.model.Pensum', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idpensum', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'}
    ]
})