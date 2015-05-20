Ext.define('GestPreRequisitos.model.Materia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateria', type: 'int'},

        {name: 'codmateria',   type: 'string'},
        {name: 'descripcion', type: 'string'}
    ]
})