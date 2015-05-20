Ext.define('GestPeriodos.view.tipoperiodo.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.periodo_tipoperiodo_combo',

    id: 'idPeriodoTipoPeriodoCombo',
    fieldLabel: 'Tipo de periodo',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    store: Ext.create('GestPeriodos.store.TipoPeriodos'),
    queryMode: 'local',
    name: 'idtipo_periododocente',
    allowBlank: false,
    labelAlign: 'top',
    displayField: 'tipoperiodo',
    valueField: 'idtipo_periododocente'
});