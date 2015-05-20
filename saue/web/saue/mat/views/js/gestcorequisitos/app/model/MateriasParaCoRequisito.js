Ext.define('GestCoRequisitos.model.MateriasParaCoRequisito', {
    extend: 'Ext.data.Model',
    //extend: 'GestMaterias.model.Materia'

    fields: [
        {name: 'idmateriaco', mapping: 'idmateria', type: 'string'},
        {name: 'codmateria', type: 'string'},
        {name: 'descripcion', type: 'string'}
    ]
})