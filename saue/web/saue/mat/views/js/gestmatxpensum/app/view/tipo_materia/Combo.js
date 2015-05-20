Ext.define('GestMatxPensum.view.tipo_materia.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.tipo_materia_combo',

    fieldLabel: 'Tipo',//Etiqueta
    editable: false,
    store: Ext.create('GestMatxPensum.store.TipoMaterias'),
    queryMode: 'local',
    name: 'idtipomateria',
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idtipomateria',
    allowBlank: false
});