Ext.define('GestMatxPensum.view.matxpensum.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.matxpensum_grid',

    store: 'GestMatxPensum.store.MateriasxPensum',

    selModel: Ext.create('Ext.selection.CheckboxModel'),

    initComponent: function () {
        var me = this;

        me.title = perfil.etiquetas.lbTltMateriaPensum;

        me.bbar = [
            Ext.create('Ext.toolbar.Paging', {
                displayInfo: true,
                store: 'GestMatxPensum.store.MateriasxPensum'
            }),
            Ext.create('Ext.form.field.Number', {
                id: 'idSumaCreditos',
                itemId: 'idSumaCreditos',
                fieldLabel: perfil.etiquetas.lbCmpSumCred,
                labelWidth:50,
                allowDecimals: true,
                width: 100,
                value: 0.00,
                hideTrigger: true,
                editable: false,
                maxText: perfil.etiquetas.lbCmpSumCredMaxText
            })
        ];

        me.tbar = Ext.widget('matxpensum_toolbar');

        me.columns = [
            { dataIndex: 'idpensumenfasismateriatipo', hidden: true, hideable: false},
            { dataIndex: 'idpensum', hidden: true, hideable: false},
            { dataIndex: 'idenfasis', hidden: true, hideable: false},
            { dataIndex: 'idmateria', hidden: true, hideable: false},
            { dataIndex: 'idarea', hidden: true, hideable: false},
            { dataIndex: 'estado', hidden: true, hideable: false},
            { header: 'Código materia', dataIndex: 'codmateria'},//eqtiquetas
            { header: 'Descripción', dataIndex: 'descripcion', flex: 1},//eqtiquetas
            { header: 'Créditos', dataIndex: 'creditos', type: 'float'}//eqtiquetas
        ];

        me.callParent(arguments);
    }
});