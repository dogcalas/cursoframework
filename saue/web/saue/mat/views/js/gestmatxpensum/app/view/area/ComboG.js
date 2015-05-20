Ext.define('GestMatxPensum.view.area.ComboG', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.area_general_combo',

    //fieldLabel: 'Área general',//Etiqueta
    editable: false,
    store: Ext.create('GestMatxPensum.store.AreasGenerales'),
    queryMode: 'local',
    name: 'idareageneral',
    id: 'idareageneral',
    displayField: 'descripcion',
    labelAlign: 'top',
    valueField: 'idareageneral',
    allowBlank: false,
    disabled: true,

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpNiveles;
        me.emptyText = perfil.etiquetas.lbEmpCombo,
        me.callParent(arguments);
    }
});