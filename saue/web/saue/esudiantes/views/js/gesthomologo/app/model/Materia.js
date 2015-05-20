Ext.define('GestHom.model.Materia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateriahomo', type: 'int'},
        {name: 'idmateria', type: 'int'},
        {name: 'descripcion',   type: 'string'},
        {name: 'idtipoaprobado', type: 'int'},
        {name: 'codigo', mapping:'codmateria',    type: 'string'},
        {name: 'paralelo', type: 'string'},
        {name: 'nota', type: 'float'},
        {name: 'materiahomo',   type: 'string'},
        {name: 'codmateriahomo',   type: 'string'}
    ]
})