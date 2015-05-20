Ext.define('GestMatxPensum.model.Area', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idarea', type: 'int', convert: null},
        {name: 'creditos', type: 'float', convert: null},
        {
            name: 'descripcion',
            type: 'string',
            convert: function (value, record) {
                /*if(value)
                    return value;*/
                var descripcion_creditos = value + ' ( ' + record.get('creditos')+' )';
                return descripcion_creditos;
            }
        }
    ]
})