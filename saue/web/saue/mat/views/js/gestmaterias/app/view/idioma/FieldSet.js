Ext.define('GestMaterias.view.idioma.FieldSet', {
    extend: 'Ext.form.FieldSet',
    alias: 'widget.materia_idioma_fieldset',
    checkboxToggle: true,
    checkboxName: 'have_idioma',
    collapsed: true,
    layout: 'hbox',

    initComponent: function () {
        var me = this;

        me.title = perfil.etiquetas.lbCmpMateriaIdioma;

        me.items = [
            {
                flex: 1,
                padding: '0 5 5 0',
                xtype: 'materia_idioma_combo'
            },
            {
                flex: 1,
                padding: '0 0 5 5',
                xtype: 'materia_idioma_nivel'
            }
        ];

        me.callParent(arguments);
    }
});