Ext.define('RegMaterias.view.materia.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.materiagrid',
    id: 'materiagrid',

    store: 'GestCursos.store.Materias',

    initComponent: function () {
        var me = this;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.tbar = Ext.create('RegMaterias.view.materia.Toolbar');

        me.columns = [
            {
                dataIndex: 'idmateria',
                hidden: true,
                hideable: false
            },
            {
                text: 'Materia',//perfil.etiquetas.lbHdrNombreProfesor,
                dataIndex: 'descripcion',
                flex: 1
            }
        ];

        this.callParent(arguments);
    }
});