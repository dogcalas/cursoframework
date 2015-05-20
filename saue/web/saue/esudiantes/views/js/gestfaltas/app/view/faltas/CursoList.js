Ext.define('GestFaltas.view.faltas.CursoList', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.cursolistf',
    id: 'cursolistf',
    store: 'GestFaltas.store.Cursos',

    columns: [
        { dataIndex: 'idcurso', hidden: true, hideable: false},
        { header: 'Aula', dataIndex: 'aula'},
        { header: 'Código', dataIndex: 'codmateria', flex: 1},
        { dataIndex: 'idmateria', hidden: true, flex: 1},
        { header: 'Materia', dataIndex: 'materia', flex: 2 },
        { header: 'Horario', dataIndex: 'horario', flex: 2},
        { header: 'Profesor', dataIndex: 'profesor', flex: 2 },
        { header: '# de alumnos', dataIndex: 'n_alumnos'}
        /*{ header: 'Paralelo', dataIndex: 'par_curs'},
        { header: '# de alumnos', dataIndex: 'numalum'}*/
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