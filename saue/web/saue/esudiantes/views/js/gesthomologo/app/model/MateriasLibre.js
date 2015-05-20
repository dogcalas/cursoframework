Ext.define('GestHom.model.MateriasLibre', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateria', type: 'int'},
        {name: 'descripcion',   type: 'string'},
        {name: 'codigo', mapping:'codmateria',    type: 'string'},
        {name: 'periodo',   type: 'string'},
        {name: 'paralelo',   type: 'string'},
        {name: 'nota',   type: 'float'}
    ]
})