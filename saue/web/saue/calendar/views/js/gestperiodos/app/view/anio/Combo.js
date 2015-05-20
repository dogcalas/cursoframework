Ext.define('GestPeriodos.view.anio.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.periodo_anio_combo',

    id: 'idPeriodoAnioCombo',
    fieldLabel: 'Año',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    store: 'GestPeriodos.store.Anio',
    queryMode: 'local',
    name: 'anno',
    allowBlank: false,
    anchor:'90%',
    labelAlign: 'top',
    displayField: 'anno',
    valueField: 'anno'
});