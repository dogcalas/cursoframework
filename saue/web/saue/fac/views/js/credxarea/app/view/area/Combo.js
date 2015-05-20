Ext.define('CredxArea.view.area.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.credxarea_area_combo',

    fieldLabel: perfil.etiquetas.lbCmpArea,
    editable: false,
    store: 'CredxArea.store.Areas',
    queryMode: 'local',
    name: 'idarea',
    labelAlign: 'top',
    valueField: 'idarea',
    displayField: 'descripcion',

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpArea;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

        this.callParent(arguments);
    }
});