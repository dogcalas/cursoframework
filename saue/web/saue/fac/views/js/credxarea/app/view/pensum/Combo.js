Ext.define('CredxArea.view.pensum.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.credxarea_pensum_combo',

    fieldLabel: perfil.etiquetas.lbCmpPensum,
    editable: false,
    store: 'CredxArea.store.Pensums',
    queryMode: 'local',
    name: 'idpensum',
    labelAlign: 'top',
    valueField: 'idpensum',
    displayField: 'descripcion',

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpPensum;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

        this.callParent(arguments);
    }
});