Ext.define('GestAreas.model.AreaGeneral', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idareageneral', type: 'int', convert: null},
        {name: 'descripcion_area_general', mapping: 'descripcion', type: 'string'}
    ]
})