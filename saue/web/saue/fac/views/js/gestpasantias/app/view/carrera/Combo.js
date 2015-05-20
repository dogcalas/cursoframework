Ext.define('GestPasantias.view.carrera.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.pasantias_carrera_combo',

    allowBlank: false,
    editable: false,
    store: 'GestPasantias.store.Carreras',
    queryMode: 'local',
    name: 'idcarrera',
    labelAlign: 'top',
    valueField: 'idcarrera',
    displayField: 'descripcion',
    disabled: true,

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpCarrera;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

        me.callParent(arguments);
    }
});