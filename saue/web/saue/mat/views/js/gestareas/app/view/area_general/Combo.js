Ext.define('GestAreas.view.area_general.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.area_general_combo',

    editable: false,
    store: Ext.create('GestAreas.store.AreasGenerales'),
    queryMode: 'local',
    name: 'idareageneral',
    labelAlign: 'top',
    displayField: 'descripcion_area_general',
    valueField: 'idareageneral',
    allowBlank: false,

    initComponent: function () {
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpNivel;

        me.callParent(arguments);
    }
});