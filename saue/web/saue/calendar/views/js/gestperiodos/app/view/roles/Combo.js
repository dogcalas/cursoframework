Ext.define('GestPeriodos.view.roles.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.periodo_roles_combo',

    id: 'idPeriodoRolCombo',
    name: 'PeriodoRolCombo',
    fieldLabel: 'Roles',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    store: 'GestPeriodos.store.Roles',
    queryMode: 'local',
    allowBlank: false,
    labelAlign: 'top',
    width: 200,
    displayField: 'denominacion',
    valueField: 'idrol'
});