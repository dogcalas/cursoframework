Ext.define('GestCursos.view.periodo.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.curso_periodo_grid',

    store: 'GestCursos.store.Periodos',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel');

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        //me.tbar = Ext.create('GestCursos.view.periodo.Toolbar');

        me.columns = [
            {
                dataIndex: 'idperiodo',
                hidden: true,
                hideable: false
            },
            {
                text: 'Descripción',//perfil.etiquetas.lbHdrNombreProfesor,
                dataIndex: 'descripcion',
                flex: 1
            }
        ];

        this.callParent(arguments);
    }
});