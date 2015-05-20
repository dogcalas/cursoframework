Ext.define('GestMateriaxMencion.model.MateriaxMencion', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmencion', type: 'int', convert: null},
        {name: 'idmateria', type: 'int', convert: null},
        {name: 'codmateria', type: 'string', default: ""},
        {name: 'descripcion', type: 'string', default: ""},
        {name: 'estado', type: 'boolean', default: true}
    ]
})