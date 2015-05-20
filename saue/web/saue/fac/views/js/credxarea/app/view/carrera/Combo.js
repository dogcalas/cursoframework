Ext.define('CredxArea.view.carrera.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.credxarea_carrera_combo',

    editable: false,
    store: 'CredxArea.store.Carreras',
    queryMode: 'local',
    name: 'idcarrera',
    labelAlign: 'top',
    valueField: 'idcarrera',
    displayField: 'descripcion',

    initComponent: function () {
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpCarrera;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

        this.callParent(arguments);
    }
});