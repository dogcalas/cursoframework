Ext.define('GestConv.model.Materia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateriaconva', type: 'int'},
        {name: 'idmateria', type: 'int'},
        {name: 'idtipoaprobado', type: 'int'},
        {name: 'descripcion',   type: 'string'},
        {name: 'codigo', mapping:'codmateria',    type: 'string'},
        {name: 'materiaprincipal', type: 'string'},
        {name: 'nota', type: 'string'}
    ]
});