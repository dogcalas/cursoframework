Ext.define('MatEst.view.matx.MatxEstLis' ,
{
	extend: 'Ext.grid.Panel',
	alias : 'widget.matxestlis',
	id:'matxestlis',
	store: 'MatEst.store.Cursos',
	title: 'Cursos disponibles',
	allowDeselect: true,
	plugins: [
        Ext.create('Ext.grid.plugin.DragDrop', {
            dragText: 'Arrastre para registrar.',
            //enableDrag: false,
            pluginId: 'matdrag'
        })
    ],
    columns: [
			{ dataIndex: 'idalumno', hidden: true, hideable: false},
			{ dataIndex: 'idcurso', hidden: true},
			{ dataIndex: 'idaula', hidden: true},
			{ dataIndex: 'idhorario', hidden: true},
			{ dataIndex: 'idprofesor', hidden: true},
			{ dataIndex: 'idmateria', hidden: true},
			{ dataIndex: 'idperiododocente', hidden: true},
   			{header: 'Aula', dataIndex: 'aula', flex: 1},
			{header: 'Cód. materia', dataIndex: 'codmateria',flex: 1},
			{header: 'Materia', dataIndex: 'materia', flex: 1},
			{header: 'Profesor', dataIndex: 'profesor', flex: 1},
			{header: 'Horario', dataIndex: 'horario', flex: 1},
			//{header: 'LV', dataIndex: 'lv', flex: 1, hidden: true},
			{header: 'Cupo', dataIndex: 'cupo', flex: 1/2},
			{header: 'Par', dataIndex: 'par_curs', flex: 1/2, hidden: true}],
	
	selModel: Ext.create('Ext.selection.RowModel', {
        id: 'idSelectionMatXAlumno',
        mode: 'SINGLE'
    }),
	
	columnLines : true, 
    
    selType: 'checkboxmodel',
	draggable: true,	
		
	initComponent: function() {
		var me = this;
		me.bbar = Ext.create('Ext.toolbar.Paging', {
            displayInfo: true,
            disabled:true,
            id:'paginator',
            store: me.store,
			width:'100%'
        });
		me.callParent(arguments);
	},
	
	 enablepagging: function()
	{
		this.getComponent('idbb').setDisabled(false);
	}
});
