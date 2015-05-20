Ext.define('GestCursos.view.materia.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.curso_materia_grid',

    store: 'GestCursos.store.Materias',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel');

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.tbar = Ext.create('GestCursos.view.materia.Toolbar');

        me.columns = [
            {
                dataIndex: 'idmateria',
                hidden: true,
                hideable: false
            },
            {
                text: 'Código',//perfil.etiquetas.lbHdrNombreProfesor,
                dataIndex: 'codmateria'
            },
            {
                text: 'Descripción',//perfil.etiquetas.lbHdrApellidosProfesor,
                dataIndex: 'descripcion',
                flex: 1
            }
        ];

        this.callParent(arguments);
    }
});