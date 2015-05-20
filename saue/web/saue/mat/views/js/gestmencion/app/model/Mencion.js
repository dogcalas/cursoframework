Ext.define('GestMateriaxMencion.model.Mencion', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idmencion', type: 'int', convert: null},
        {name: 'cant_materias', type: 'int', convert: null},
        {name: 'descripcion', type: 'string'},

        {name: 'descripcion_cantidad',
            type: 'string',
            convert: function (value, record) {
                if (value)
                    return value;
                var descripcion_cantidad = record.get('descripcion') + ' ( ' + record.get('cant_materias') + ' )';
                return descripcion_cantidad;
            }
        }
    ]
})