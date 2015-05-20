Ext.define('GestMaterias.model.Materia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateria', type: 'int'},
        {name: 'idtipomateria', type: 'int', convert: null},
        {name: 'ididioma', type: 'int', convert: null},
        {name: 'ididiomam', type: 'int', convert: null},

        {name: 'codmateria',   type: 'string'},
        {name: 'descripcion', type: 'string'},
        {name: 'creditos', type: 'float'},
        {name: 'min_nota_materia', type: 'float'},
        {name: 'nivel', type: 'int', convert: null},
        {name: 'traduccion', type: 'string'},
        {name: 'descripcion_area', type: 'string'},
        {name: 'descripcion_idioma', type: 'string'},
        {name: 'estado', type: 'boolean', default: true},
        {name: 'obligatoria', type: 'boolean', default: true},
        {name: 'have_idioma', type: 'boolean', default: true}
    ]
})