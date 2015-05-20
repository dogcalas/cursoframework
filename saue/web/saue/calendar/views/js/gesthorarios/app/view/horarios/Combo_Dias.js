Ext.define('GestHorarios.view.horarios.Combo_Dias', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.horarios_combo_dias',

    id: 'idHorariosDiasCombo',
    fieldLabel: 'Día',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    store: 'GestHorarios.store.Dias',
    queryMode: 'local',
    width: 90,
    padding: '0 0 0 10',
    name: 'iddias',
    allowBlank: false,
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'iddias'
});
