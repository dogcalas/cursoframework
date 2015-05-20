Ext.define('GestMateriaxMencion.model.Materia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateria', type: 'int', convert: null},
        {name: 'codmateria', type: 'string'},
        {name: 'descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: true}
    ]
})