Ext.define('GestMatxPensum.view.materia.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.matxpensum_materia_grid',

    store: 'GestMatxPensum.store.Materias',

    selModel: Ext.create('Ext.selection.CheckboxModel'),

    initComponent: function () {
        var me = this;

        me.title = perfil.etiquetas.lbTltMateria;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store//'GestMatxPensum.store.Materias'
        });

        me.tbar = Ext.create('GestMatxPensum.view.materia.Toolbar');

        me.columns = [
            { dataIndex: 'idmateria', hidden: true},
            { header: 'Código materia', dataIndex: 'codmateria'},//eqtiquetas
            { header: 'Descripción', dataIndex: 'descripcion', flex: 1},//eqtiquetas
            { header: 'Créditos', dataIndex: 'creditos', type: 'float' },//eqtiquetas
            { dataIndex: 'estado', hidden: true, hideable: false}
        ];

        me.callParent(arguments);
    }
});