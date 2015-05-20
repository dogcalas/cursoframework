Ext.define('GestMaterias.model.Credito', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmateriacredito', type: 'int', convert: null},
        {name: 'idpensum', type: 'int', convert: null},
        {name: 'idmateria', type: 'int', convert: null},
        {name: 'descripcion',type: 'string'},
        {name: 'creditos', type: 'float', 'defaultValue': 0 }
    ]
})