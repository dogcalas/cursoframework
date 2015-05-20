Ext.define('GestPensums.view.facultad.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.pensum_facultad_combo',

    fieldLabel: 'Facultad',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    store: Ext.create('GestPensums.store.Facultades'),
    queryMode: 'local',
    name: 'idfacultad',
    labelAlign: 'top',
    displayField: 'denominacion',
    valueField: 'idfacultad'
});