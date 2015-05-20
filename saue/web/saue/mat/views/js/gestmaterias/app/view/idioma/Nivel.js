Ext.define('GestMaterias.view.idioma.Nivel', {
    extend: 'Ext.form.field.Number',
    alias: 'widget.materia_idioma_nivel',

    name: 'nivel',
    minValue: 1,
    allowDecimals: false,
    allowBlank: false,
    disabled: true,
    labelAlign: 'top',

    initComponent: function(){
        var me = this;

        me.fieldLabel = perfil.etiquetas.lbCmpNivel;
        me.emptyText = perfil.etiquetas.lbEmpCombo;
    }
});