Ext.define('GestTipoPeriodo.model.TipoPeriodo', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipo_periododocente', type: 'int', convert: null},
        {name: 'tipoperiodo', mapping: 'tipoperiodo', type: 'string'},
        {name: 'duracion', mapping: 'duracion', type: 'int'},
        {name: 'estado', type: 'boolean', default: 'true'}
    ]
})
