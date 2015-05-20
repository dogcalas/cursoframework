Ext.define('GestCursos.view.alumno.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.curso_alumno_grid',

    store: 'GestCursos.store.Alumnos',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.RowModel');

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            store: me.store
        });

        me.tbar = Ext.create('GestCursos.view.alumno.Toolbar');

        me.columns = [
            {
                dataIndex: 'idalumno',
                hidden: true,
                hideable: false
            },
            {
                text: 'Código alumno',//perfil.etiquetas.lbHdrApellidosProfesor,
                dataIndex: 'codigo'
            },
            {
                text: 'Nombre',//perfil.etiquetas.lbHdrApellidosProfesor,
                dataIndex: 'nombre_completo',
                flex: 2
            },
            {
                text: 'Facultad',//perfil.etiquetas.lbHdrNombreProfesor,
                dataIndex: 'facultad',
                flex: 1/2
            },
            {
                text: 'Nota',//perfil.etiquetas.lbHdrNombreProfesor,
                dataIndex: 'nota',
                flex: 1/3
            },
            {
                text: 'Faltas',//perfil.etiquetas.lbHdrNombreProfesor,
                dataIndex: 'falta',
                flex: 1/3
            },
            {
                text: 'Detalles',//perfil.etiquetas.lbHdrNombreProfesor,
                dataIndex: 'tipoaprobado',
                flex: 1
            }
        ];

        this.callParent(arguments);
    }
});