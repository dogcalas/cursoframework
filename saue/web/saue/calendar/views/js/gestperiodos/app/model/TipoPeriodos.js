Ext.define('GestPeriodos.model.TipoPeriodos', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipo_periododocente', type: 'int', convert: null},
        {name: 'tipoperiodo', type: 'string', convert: null},
        {name: 'duracion', type: 'int', convert: null}
    ]
})