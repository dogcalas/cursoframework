Ext.define('GestMateriaxMencion.view.materia.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.materiaxmencion_materia_grid',

    store: 'GestMateriaxMencion.store.Materias',

    selModel: Ext.create('Ext.selection.RowModel'),

    initComponent: function () {
        var me = this;

        me.title = perfil.etiquetas.lbTtlMaterias;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store//'GestMateriaxMencion.store.Materias'
        });

        me.tbar = Ext.create('GestMateriaxMencion.view.materia.Toolbar');

        me.columns = [
            { dataIndex: 'idmateria', hidden: true},
            { header: 'Código materia', dataIndex: 'codmateria'},//etiquetas
            { header: 'Descripción', dataIndex: 'descripcion', flex: 1},//etiquetas
            { text: 'Activado',dataIndex: 'estado', hidden: true}//etiquetas
        ];

        me.callParent(arguments);
    }
});