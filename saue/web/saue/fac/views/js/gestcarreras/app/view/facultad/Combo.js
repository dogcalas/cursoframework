Ext.define('GestCarreras.view.facultad.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.carrera_facultad_combo',

    fieldLabel: 'Facultad',//Etiqueta
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    store: Ext.create('GestCarreras.store.Facultades'),
    queryMode: 'local',
    name: 'idfacultad',
    labelAlign: 'top',
    displayField: 'denominacion',
    valueField: 'idfacultad',

    initComponent: function(properties){
        var me = this;
        Ext.apply(this, properties);

        this.callParent();
    }
});