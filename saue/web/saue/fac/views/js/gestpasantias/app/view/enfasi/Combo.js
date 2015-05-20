Ext.define('GestPasantias.view.enfasi.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.pasantias_enfasi_combo',

    allowBlank: false,
    editable: false,
    store: 'GestPasantias.store.Enfasis',
    queryMode: 'local',
    name: 'idenfasis',
    labelAlign: 'top',
    valueField: 'idenfasis',
    displayField: 'descripcion',
    disabled: true,

    initComponent: function(){
        var me = this;

        me.fieldLabel= perfil.etiquetas.lbCmpEnfasis;
        me.emptyText= perfil.etiquetas.lbEmpCombo,

        me.callParent(arguments);
    }
});