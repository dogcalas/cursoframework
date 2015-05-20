Ext.define('GestConv.model.MateriasConva', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateriaconva', type: 'int'},
        {name: 'idmateria', type: 'int'},
        {name: 'descripcion',   type: 'string'},
        {name: 'principal',   type: 'boolean'}
    ]
})