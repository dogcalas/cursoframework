Ext.define('GestCursos.view.curso.Grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.curso_grid',

    store: 'GestCursos.store.Cursos',

    initComponent: function () {
        var me = this;

        me.selModel = Ext.create('Ext.selection.CheckboxModel');

        me.features = [{
            ftype: 'grouping',
            hideGroupedHeader: true
        }];

        me.bbar = [
            Ext.create('Ext.toolbar.Paging', {
                displayInfo: true,
                store: me.store
            }),
            '->',
            Ext.create('Ext.toolbar.Toolbar', {
                items: [
                    {
                        xtype: 'datefield',
                        labelWidth: 60,
                        fieldLabel: 'Fecha',//Etiqueta
                        format: "D, d M Y",
                        id: 'fecha_rpt'
                    },
                    {
                        text: 'Imprimir listado',
                        icon: perfil.dirImg + 'imprimir.png',
                        disabled: true,
                        action: 'imprimirasistencia'
                    }
                ]
            })
        ];

        me.tbar = Ext.widget('curso_toolbar');

        me.viewConfig = {
            getRowClass: function (record) {
                if (record.get('estado') === false)
                    return 'FilaRoja';
            }
        };

        me.columns = [
            {
                dataIndex: 'idcurso',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idprofesor',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idhorario',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idaula',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idmateria',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idperiododocente',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'idfacultad',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'cupo',
                hidden: true,
                hideable: false
            },
            {
                dataIndex: 'periodo_descripcion',
                hidden: true,
                hideable: false
            },
            {
                text: perfil.etiquetas.lbHdrAula,
                dataIndex: 'aula_descripcion'
            },
            {
                text: perfil.etiquetas.lbHdrMateria,
                dataIndex: 'materia_descripcion',
                flex: 3
            },
            {
                text: perfil.etiquetas.lbHdrProfesor,
                dataIndex: 'profesor_nombre_completo',
                flex: 2
            },
            {
                text: perfil.etiquetas.lbHdrHorario,
                dataIndex: 'horario_descripcion',
                flex: 1.5
            },
            {
                text: perfil.etiquetas.lbHdrNroAlum,
                dataIndex: 'n_alumnos',
                width: 60
            },
            {
                text: perfil.etiquetas.lbHdrPara,
                dataIndex: 'par_curs',
                width: 60
            },
            {
                text: perfil.etiquetas.lbHdrCupDisp,
                xtype: 'templatecolumn',
                tpl: '{[values.cupo - values.n_alumnos]} / {cupo}',
                width: 60
            },
            {
                text: perfil.etiquetas.lbHdrEstado,
                dataIndex: 'estado',
                hidden: true,
                xtype: 'booleancolumn'
            }
        ];

        this.callParent(arguments);
    }
});