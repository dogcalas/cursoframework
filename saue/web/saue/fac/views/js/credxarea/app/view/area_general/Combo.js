Ext.define('CredxArea.view.area_general.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.credxarea_area_general_combo',

    fieldLabel: perfil.etiquetas.lbCmpAreaGeneral,
    editable: false,
    store: 'CredxArea.store.AreasGenerales',
    queryMode: 'local',
    labelAlign: 'top',
    name: 'idareageneral',
    valueField: 'idareageneral',
    displayField: 'descripcion',

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpAreaGeneral;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

        this.callParent(arguments);
    }
});