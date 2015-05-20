Ext.define('GestMaterias.view.idioma.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.materia_idioma_combo',

    id: 'idMateriasIdiomasCombo',
    editable: false,
    store: Ext.create('GestMaterias.store.Idiomas'),
    queryMode: 'local',
    name: 'ididioma',
    valueField: 'ididioma',
    displayField: 'descripcion',
    allowBlank: false,
    value: null,
    labelAlign: 'top',
    disabled: true,

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpIdioma;
        me.emptyText = perfil.etiquetas.lbEmpCombo;

        me.callParent(arguments);
    }
});