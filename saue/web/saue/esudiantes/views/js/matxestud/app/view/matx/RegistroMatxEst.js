Ext.define('MatEst.view.matx.RegistroMatxEst',
    {
        extend: 'Ext.grid.Panel',
        alias: 'widget.regmatxest',
        id: 'regmatxest',
        title: 'Cursos registrados',
        selType: 'rowmodel',
        allowDeselect: true,
        viewConfig: {
            plugins: {
                ptype: 'gridviewdragdrop',
                dragText: 'Drag and drop to reorganize',
                enableDrag: false
            }
        },

	store: 'MatEst.store.Registro',
	
    columns: [
			{ dataIndex: 'idalumno', hidden: true, hideable: false},
			{ dataIndex: 'idcurso', hidden: true},
			{ dataIndex: 'idaula', hidden: true},
			{ dataIndex: 'idhorario', hidden: true},
			{ dataIndex: 'idprofesor', hidden: true},
			{ dataIndex: 'idmateria', hidden: true},   			
			{header: 'Materia', dataIndex: 'materia', flex: 1},
        //{header: 'Código', dataIndex: 'codmateria', flex: 1},
			{header: 'Horario', dataIndex: 'horario', flex: 1},
			{header: 'Profesor', dataIndex: 'profesor', flex: 1},
			{header: 'Aula', dataIndex: 'aula', flex: 1}],
	
	selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionIdiomaAlumno',
        mode: 'SINGLE'
    }),
        initComponent: function () {
            var me = this;
            me.bbar = [
                Ext.create('Ext.toolbar.Paging', {
                    displayInfo: true,
                    disabled: true,
                    id: "paginador",
                    store: me.store,
                    width: '100%'
                })
            ];

            me.tbar = [
                {
                    xtype: 'button',
                    text: perfil.etiquetas.lbBtnEliminar,
                    action: 'eliminar',
                    id: 'delboton',
                    disabled: true,
                    icon: perfil.dirImg + 'eliminar.png',
                    iconCls: 'btn'
                },
                '->',
                {
                    xtype: 'button',
                    id: 'studentinfo',
                    text: "Alumno",
                    icon: perfil.dirImg + 'buscar.png',
                    iconCls: 'btn',
                    action: 'buscar'
                },
                {
                    xtype: 'button',
                    id: 'reg',
                    layout: 'center',
                    disabled: true,
                    text: "Registrar",
                    icon: perfil.dirImg + 'avanzada.png',
                    iconCls: 'btn',
                    action: 'registrar'
                },
                {
                    xtype: 'button',
                    id: 'imp',
                    layout: 'center',
                    disabled: false,
                    text: "Imprimir",
                    icon: perfil.dirImg + 'imprimir.png',
                    iconCls: 'btn',
                    action: 'imprimir'
                }
            ];
            me.callParent(arguments);
        },
        enablepagging: function()
        {
            this.getComponent('idbb').setDisabled(false);
        }
    });
