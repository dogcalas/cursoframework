Ext.define('GestEnfasis.view.carrera.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.enfasis_carrera_combo',

    id: 'idEnfasisCarrerasCombo',
    allowBlank: false,
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    store: 'GestEnfasis.store.Carreras',
    queryMode: 'local',
    name: 'idcarrera',
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idcarrera',

    initComponent: function(){
        var me = this;

        me.fieldLabel= perfil.etiquetas.lbHdrDescripcionCarrera;

        me.callParent(arguments);
    }
});