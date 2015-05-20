Ext.define('GestPeriodos.model.Activ_Activas', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idperiodoactivo', type: 'int', convert: null},
        {name: 'idperiododocente', type: 'int', convert: null},
        {name: 'idrol', type: 'int', convert: null},
        {name: 'idfuncionalidad', type: 'int', convert: null},
        {name: 'idaccion', type: 'int'},
        {name: 'fecha_ini', type: 'date'},
        {name: 'fecha_fin', type: 'date'},
        {name: 'rol', type: 'string'},
        {name: 'funcionalidad', type: 'string'},
        {name: 'accion', type: 'string'},
        {name: 'idsistema', type: 'string'}
    ]
});