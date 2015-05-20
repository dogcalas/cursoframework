Ext.define('GestNotas.view.nota.CursoList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.cursolist',

    store: 'GestNotas.store.Cursos',

    columns: [
        { dataIndex: 'idcurso', hidden: true, hideable: false},
        { header: 'Aula', dataIndex: 'aula', flex: 1},
        { header: 'Código', dataIndex: 'codmateria', width: 60},
        { dataIndex: 'idmateria', hidden: true, flex: 1},
        { header: 'Materia', dataIndex: 'materia_descripcion', flex: 2 },
        { header: 'Horario', dataIndex: 'horario', flex: 2},
        { header: 'Profesor', dataIndex: 'profesor', flex: 2 },
        { header: 'Paralelo', dataIndex: 'par_curs', width: 40},
        { header: '# de alumnos', dataIndex: 'n_alumnos', width: 30}
    ],

    initComponent: function () {
        var me = this;

        me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
           store: me.store
        });

        me.callParent(arguments);
    }
});