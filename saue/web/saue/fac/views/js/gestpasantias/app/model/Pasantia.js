Ext.define('GestPasantias.model.Pasantia', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idpasantia', mapping: 'idpractica', type: 'int', convert: null},
        {name: 'idfacultad', type: 'int', convert: null},
        {name: 'idcarrera', type: 'int', convert: null},
        {name: 'idenfasis', type: 'int', convert: null},
        {name: 'idtipopasantia', mapping: 'idtipopractica', type: 'int', convert: null},

        {name: 'empresa', type: 'string' },
        {name: 'tipo_pasantia_descripcion', mapping: 'DatTipoPasantia.descripcion', type: 'string' },
        {name: 'horas', type: 'int'},
        {name: 'estado', type: 'bool', default: true }
    ]
})