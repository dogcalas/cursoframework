Ext.define('GestMaterias.view.tipo_materia.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.tipo_materia_combo',

    editable: false,
    store: Ext.create('GestMaterias.store.TipoMaterias'),
    queryMode: 'local',
    name: 'idtipomateria',
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idtipomateria',
    //emptyText: perfil.etiquetas.lbEmpCombo,
    allowBlank: false,

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpTipoMateria;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

            me.callParent(arguments);
    }
});