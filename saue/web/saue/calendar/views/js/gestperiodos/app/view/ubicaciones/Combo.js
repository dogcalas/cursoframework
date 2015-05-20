Ext.define('GestPeriodos.view.ubicaciones.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.periodo_ubicacion_combo',

    id: 'idPeriodoUbicacionCombo',
    fieldLabel: 'Identificador',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    allowBlank: false,
    editable: false,
    store: Ext.create('GestPeriodos.store.Ubicaciones'),
    queryMode: 'local',
    name: 'idcampus',
    anchor: '100%',
    padding: '0 0 0 10',
    labelAlign: 'top',
    displayField: 'abrev',
    valueField: 'idcampus'
});