Ext.define('GestMenciones.view.facultad.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.mencion_facultad_combo',

    fieldLabel: 'Facultad',//Etiqueta
    editable: false,
    emptyText: '--Seleccione--',
    store: 'GestMenciones.store.Facultades',
    queryMode: 'local',
    name: 'idfacultad',
    labelAlign: 'top',
    displayField: 'denominacion',
    valueField: 'idfacultad'
});