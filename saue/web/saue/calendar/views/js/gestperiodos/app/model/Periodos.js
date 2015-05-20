Ext.define('GestPeriodos.model.Periodos', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idperiododocente', type: 'int', convert: null},
        {name: 'idtipo_periododocente', type: 'int', convert: null},
        {name: 'idcampus', type: 'int', convert: null},
        {name: 'descripcion', type: 'string', convert: null},
        {name: 'tipoperiodo', mapping: 'tipoperiodo', type: 'string'},
        {name: 'estado', mapping: 'estado', type: 'boolean'},
        {name: 'codperiodo', mapping: 'codperiodo', type: 'string'},
        {name: 'anno', type: 'int', convert: null},
        {name: 'abrev', type: 'string', mapping: 'abrev'},
        {name: 'anno', type: 'int', mapping: 'anno'},
        {name: 'duracion', type: 'int', mapping: 'duracion'},
        {name: 'fecha_ini', type: 'string', mapping: 'fecha_ini'},
        {name: 'fecha_fin', type: 'string', mapping: 'fecha_fin'},
        {name: 'cant_notas', type: 'int', mapping: 'cant_notas'}
    ]
});