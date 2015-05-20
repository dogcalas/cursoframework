Ext.define('GestCoRequisitos.model.CoRequisito', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idco_requisito', type: 'int', convert: null},
        {name: 'idmateria', type: 'int', convert: null},
        {name: 'idmateriaco', type: 'int', convert: null},
        {name: 'codmateria', mapping: 'DatMateria.codmateria',  type: 'string'},
        {name: 'descripcion', mapping:'DatMateria.descripcion', type: 'string'}
    ]
})