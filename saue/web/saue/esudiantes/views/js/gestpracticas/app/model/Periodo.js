Ext.define('PracWin.model.Periodo', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idperiododocente', type: 'int', convert: null},
        {name: 'periodo', mapping: 'descripcion', type: 'string'}
    ]
});
