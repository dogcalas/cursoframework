Ext.define('GestMatxPensum.view.pensum.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.pensum_combo',

    editable: false,
    store: Ext.create('GestMatxPensum.store.Pensums'),
    queryMode: 'local',
    name: 'idpensum',
    id: 'idpensum',
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idpensum',
    allowBlank: false,
    disabled: true,

    initComponent: function () {
        var me = this;
        me.fieldLabel = perfil.etiquetas.lbCmpPensum;
        me.emptyText = perfil.etiquetas.lbEmpCombo;
        me.callParent();
    }
});