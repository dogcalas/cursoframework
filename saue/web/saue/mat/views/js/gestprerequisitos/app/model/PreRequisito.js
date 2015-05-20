Ext.define('GestPreRequisitos.model.PreRequisito', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idpre_requisito', type: 'int', convert: null},
        {name: 'idmateria', type: 'int', convert: null},
        {name: 'idmateriapre', type: 'int', convert: null},
        {name: 'codmateria', mapping: 'DatMateria.codmateria',  type: 'string'},
        {name: 'descripcion', mapping:'DatMateria.descripcion', type: 'string'}
    ]
})