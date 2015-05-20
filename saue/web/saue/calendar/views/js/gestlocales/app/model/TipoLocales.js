Ext.define('GestLocales.model.TipoLocales', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idtipo_aula', type: 'int', convert: null},
        {name: 'descripcion', type: 'string', convert: null}
    ]
})