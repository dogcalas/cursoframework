Ext.define('GestPensums.view.carrera.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.pensum_carrera_combo',

    id: 'idPensumCarrerasCombo',
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    allowBlank: false,
    editable: false,
    store: 'GestPensums.store.Carreras',
    queryMode: 'local',
    name: 'idcarrera',
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idcarrera'
});