Ext.define('GestMatxPensum.view.area.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.area_combo',

    //id: 'idMateriasAreasCombo',
    //fieldLabel: perfil.etiquetas.Campos,
    emptyText: '--Seleccione--',//perfil.etiquetas.lbEmpCombo,
    editable: false,
    allowBlank: false,
    store: 'GestMatxPensum.store.Areas',
    queryMode: 'local',
    name: 'idarea',
    id: 'idarea',
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idarea',
    disabled: true,

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpCampos;
        me.callParent(arguments);
    }
});
