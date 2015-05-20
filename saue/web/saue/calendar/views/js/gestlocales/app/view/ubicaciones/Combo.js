Ext.define('GestLocales.view.ubicaciones.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.local_ubicacion_combo',

    id: 'idLocalUbicacionCombo',
    fieldLabel: 'Ubicación',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    allowBlank: false,
    editable: false,
    store: Ext.create('GestLocales.store.Ubicaciones'),
    queryMode: 'local',
    name: 'idcampus',
    labelAlign: 'top',
    displayField: 'abrev',
    valueField: 'idcampus'
});