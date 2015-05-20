Ext.define('GestAreas.model.Area', {
    extend: 'Ext.data.Model',

    fields: [
        {name: 'idarea', type: 'int', convert: null},
        {name: 'idareageneral', mapping: 'NomAreaGeneral.idareageneral', type: 'int', convert: null},
        {name: 'descripcion_area', mapping: 'descripcion', type: 'string'},
        {name: 'descripcion_area_general', mapping: 'NomAreaGeneral.descripcion', type: 'string'},
        {name: 'estado', type: 'boolean', default: true}
    ]
})