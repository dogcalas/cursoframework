Ext.define('GestMatxPensum.model.Materia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateria', type: 'int', convert: null},
        {name: 'codmateria', type: 'string'},
        {name: 'descripcion', type: 'string'},
        {name: 'creditos', type: 'float'}
    ]
})