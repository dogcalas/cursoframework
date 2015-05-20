Ext.define('GestCursos.view.profesor.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.curso_profesor_grid',

    store: 'GestCursos.store.Profesores',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel');

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.tbar = Ext.create('GestCursos.view.profesor.Toolbar');

        me.columns = [
            {
                dataIndex: 'idprofesor',
                hidden: true,
                hideable: false
            },
            {
                text: 'Nombre',//perfil.etiquetas.lbHdrNombreProfesor,
                dataIndex: 'nombre',
                flex: 1
            },
            {
                text: 'Apellidos',//perfil.etiquetas.lbHdrApellidosProfesor,
                dataIndex: 'apellidos',
                flex: 1
            }
        ];

        this.callParent(arguments);
    }
});