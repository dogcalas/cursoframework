Ext.define('GestLocales.view.tipolocales.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.local_tipolocal_combo',

    id: 'idLocalTipoLocalCombo',
    fieldLabel: 'Tipo de ambiente de aprendizaje',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    store: 'GestLocales.store.TipoLocales',
    queryMode: 'local',
    name: 'idtipo_aula',
    allowBlank: false,
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idtipo_aula'
});
