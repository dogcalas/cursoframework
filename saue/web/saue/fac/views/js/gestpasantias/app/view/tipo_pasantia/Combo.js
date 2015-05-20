Ext.define('GestPasantias.view.tipo_pasantia.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.tipo_pasantias_combo',

    allowBlank: false,
    editable: false,
    store: 'GestPasantias.store.TipoPasantias',
    queryMode: 'local',
    name: 'idtipopasantia',
    valueField: 'idtipopasantia',
    displayField: 'descripcion',
    labelAlign: 'top',

    initComponent: function () {
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpTipoPasantia;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

        me.callParent(arguments);
    }
});