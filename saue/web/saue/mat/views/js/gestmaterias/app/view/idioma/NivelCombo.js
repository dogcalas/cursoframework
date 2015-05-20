Ext.define('GestMaterias.view.idioma.NivelCombo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.materia_idioma_nivel_combo',

    fieldLabel: 'Nivel',//Etiqueta
    editable: false,
    store: Ext.create('Ext.data.Store', {
        fields: ['nivel'],
        data: [
            { nivel: 1 },
            { nivel: 2 },
            { nivel: 3 },
            { nivel: 4 },
            { nivel: 5 },
            { nivel: 6 },
            { nivel: 7 },
            { nivel: 8 }
        ]
    }),
    queryMode: 'local',
    name: 'nivel',
    labelAlign: 'top',
    displayField: 'nivel',
    valueField: 'nivel',
    emptyText: perfil.etiquetas.lbEmpCombo,
    allowBlank: false
});