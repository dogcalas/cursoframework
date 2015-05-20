Ext.define('CredxArea.model.CredxArea', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idareacredito', type: 'int', convert: null},
        {name: 'idenfasis', type: 'int', convert: null},
        {name: 'idpensum', type: 'int', convert: null},
        {name: 'idcarrera', type: 'int', convert: null},
        {name: 'idfacultad', type: 'int', convert: null},
        {name: 'idareageneral', type: 'int', convert: null},
        {name: 'idarea', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'},
        {name: 'creditos', type: 'float'},
        {name: 'estado', type: 'bool'}
    ]
})