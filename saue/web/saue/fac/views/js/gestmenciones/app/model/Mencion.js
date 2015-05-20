Ext.define('GestMenciones.model.Mencion', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmencion', type: 'int'},
        {name: 'idfacultad', type: 'int', convert: null},
        {name: 'cant_materias', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'},
        {name: 'denominacion', type: 'string'},
        {name: 'estado', type: 'bool'}
    ]
})