Ext.define('GestMatxPensum.model.MateriaxPensum', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idpensumenfasismateriatipo', type: 'int', convert: null},
        {name: 'idpensum', type: 'int', convert: null},
        {name: 'idenfasis', type: 'int', convert: null},
        {name: 'idarea', type: 'int', convert: null},
        {name: 'idmateria', type: 'int', convert: null},
        {name: 'codmateria', type: 'string', default: ""},
        {name: 'descripcion', type: 'string', default: ""},
        {name: 'creditos', type: 'float', default: 0.00},
        {name: 'estado', type: 'boolean', default: true}
    ]
})