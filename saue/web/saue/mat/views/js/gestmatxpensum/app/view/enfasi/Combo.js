Ext.define('GestMatxPensum.view.enfasi.Combo', {
    extend: 'Ext.form.field.ComboBox',
    alias: 'widget.enfasi_combo',

    id: 'idMatxPensumEnfasisCombo',
    editable: false,
    store: Ext.create('GestMatxPensum.store.Enfasis'),
    queryMode: 'local',
    name: 'idenfasis',
    labelAlign: 'top',
    displayField: 'descripcion',
    valueField: 'idenfasis',
    allowBlank: false,
    /*listConfig: {
     loadingText: 'Cargando énfasis...',//Etiquetas
     emptyText: 'No existen énfasis.'//Etiquetas
     },
     pageSize: 10,*/
    disabled: true,

    initComponent: function () {
        var me = this;
        me.fieldLabel = perfil.etiquetas.lbCmpItinerario;
        me.emptyText = perfil.etiquetas.lbEmpCombo;
        me.callParent();
    }
});