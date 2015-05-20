Ext.define('GestCursos.view.horario.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.curso_horario_grid',

    store: 'GestCursos.store.Horarios',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel');

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.tbar = Ext.create('GestCursos.view.horario.Toolbar');

        me.columns = [
            {
                dataIndex: 'idhorario',
                hidden: true,
                hideable: false
            },
            {
                text: 'Descripción',//perfil.etiquetas.lbHdrNombreHorario,
                dataIndex: 'horario_descripcion',
                flex: 1
            }
        ];

        this.callParent(arguments);
    }
});