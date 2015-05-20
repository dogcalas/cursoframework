Ext.define('GestMateriaxMencion.view.mencion.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.mencion_combo',

    //id: 'idMateriasAreasCombo',
    editable: false,
    allowBlank: false,
    store: 'GestMateriaxMencion.store.Menciones',
    queryMode: 'local',
    name: 'idmencion',
    labelAlign: 'top',
    valueField: 'idmencion',
    displayField: 'descripcion_cantidad',
    labelWidth: 60,
    width: 400,

    initComponent: function () {

        this.fieldLabel = perfil.etiquetas.lbCmpMencion;
        this.emptyText = perfil.etiquetas.lbEmpCombo;

        this.callParent(arguments);
    }
});
