Ext.define('GestMaterias.view.area.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.materia_area_combo',

    id: 'idMateriasAreasCombo',
    fieldLabel: 'Área',//perfil.etiquetas.lbHdrDescripcionArea,
    emptyText: perfil.etiquetas.lbEmpCombo,
    editable: false,
    allowBlank: false,
    store: Ext.create('GestMaterias.store.Areas'),
    queryMode: 'local',
    name: 'descripcion_area',
    labelAlign: 'top',
    displayField: 'descripcion_area',
    valueField: 'idarea'
});