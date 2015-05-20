Ext.define('GestPeriodos.model.Accion', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'm__idaccion', type: 'int', convert: null},
        {name: 'm__idfuncionalidad', type: 'int', convert: null},
        {name: 'idrol', type: 'int', convert: null},
        {name: 'm__denominacion', type: 'string', convert: null},
        {name: 'checked', type: 'boolean', convert: null}
    ]
});
