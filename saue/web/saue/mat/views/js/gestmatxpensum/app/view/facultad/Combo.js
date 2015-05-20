Ext.define('GestMatxPensum.view.facultad.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.facultad_combo',
    id: 'facultad_combo',
    editable: false,
    store: Ext.create('GestMatxPensum.store.Facultades'),
    queryMode: 'local',
    name: 'idfacultad',
    labelAlign: 'top',
    displayField: 'denominacion',
    valueField: 'idfacultad',
    allowBlank: false,

    initComponent: function () {
        var me = this;
        me.fieldLabel = perfil.etiquetas.lbCmpFacultad;
        me.emptyText = perfil.etiquetas.lbEmpCombo;
        me.callParent();
    }
});