Ext.define('GestEnfasis.view.facultad.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.enfasis_facultad_combo',

    fieldLabel: 'Facultad',//Etiqueta
    editable: false,
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    store: Ext.create('GestEnfasis.store.Facultades'),
    queryMode: 'local',
    name: 'idfacultad',
    labelAlign: 'top',
    allowBlank: false,
    displayField: 'denominacion',
    valueField: 'idfacultad'
});