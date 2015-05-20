Ext.define('GestMatxPensum.view.carrera.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.carrera_combo',

    id: 'idMatxPensumCarrerasCombo',
    allowBlank: false,
    editable: false,
    store: Ext.create('GestMatxPensum.store.Carreras'),
    queryMode: 'local',
    name: 'idcarrera',
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idcarrera',
    disabled: true,

    initComponent: function () {
        var me = this;
        me.fieldLabel = perfil.etiquetas.lbCmpCarrera;
        me.emptyText = perfil.etiquetas.lbEmpCombo;
        me.callParent();
    }
});