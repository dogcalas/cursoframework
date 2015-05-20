Ext.define('GestCoRequisitos.view.materia.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.corequisitos_materia_grid',

    store: Ext.create('GestCoRequisitos.store.Materias'),

    initComponent: function () {
        var me = this;

        me.columns= [
            { dataIndex: 'idmateria', hidden: true, hideable: false },
            { header: perfil.etiquetas.lbHdrCodMateria, dataIndex: 'codmateria'},
            { header: perfil.etiquetas.lbHdrDescripcion, dataIndex: 'descripcion', flex: 1}
        ];

        me.bbar = Ext.create('GestCoRequisitos.view.materia.PagingToolBar');

        me.tbar = Ext.create('GestCoRequisitos.view.materia.ToolBar');

        me.selModel = Ext.create('Ext.selection.RowModel', {
            mode: 'SINGLE'
        })

        me.callParent(arguments);
    }
});