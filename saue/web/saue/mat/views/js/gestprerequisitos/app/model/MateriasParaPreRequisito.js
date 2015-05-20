Ext.define('GestPreRequisitos.model.MateriasParaPreRequisito', {
    extend: 'Ext.data.Model',
    //extend: 'GestMaterias.model.Materia'

    fields: [
        {name: 'idmateriapre', mapping: 'idmateria', type: 'string'},
        {name: 'codmateria', type: 'string'},
        {name: 'descripcion', type: 'string'}
    ]
})