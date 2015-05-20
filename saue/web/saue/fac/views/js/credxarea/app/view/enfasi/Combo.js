Ext.define('CredxArea.view.enfasi.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.credxarea_enfasi_combo',

    fieldLabel: perfil.etiquetas.lbCmpEnfasis,
    editable: false,
    store: 'CredxArea.store.Enfasis',
    queryMode: 'local',
    name: 'idenfasis',
    labelAlign: 'top',
    valueField: 'idenfasis',
    displayField: 'descripcion',

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpEnfasis;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

        this.callParent(arguments);
    }
});