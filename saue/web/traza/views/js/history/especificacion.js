Ext.QuickTips.init();
var perfil = window.parent.UCID.portal.perfil
perfil.etiquetas = Object();
var storegrid,gdGestionHis,cmGestionhist;
var xg = Ext.grid;
var btnRollback = new Ext.Button({
	handler : rollback,
	text : 'Rollback',
	disabled : true,
	id : 'btnAdd',
	icon : perfil.dirImg + 'deshacer.png',
	iconCls : 'btn'
});
var fecha = new Ext.form.DateField({
	    xtype:'datefield',
		allowBlank:false,
		format:'d/m/Y',
		fieldLabel: 'Fecha',
		readOnly:true,
		blankText:"Fecha",
		disabled : true,
		listeners:{change : function(){
		if(storegrid.getCount()>0)btnRollback.enable();else {fecha.reset();fecha.disable();btnRollback.disable()}}},
		id:'fecha',
		name:'fecha',
		anchor:'85%'
})

function cargarInterfaz(){
	storegrid = new Ext.data.Store({
		url : '',
		reader : new Ext.data.JsonReader({
			totalProperty : "totalProperty",
			root : "root"
		}, [{
			name : 'vacio'
		}])
	});
	stcombo = new Ext.data.Store({
		url:'cargartablas',
		listeners : {'load' : function() {}},
			reader : new Ext.data.JsonReader({
				root : "datos"
			}, [
				{name : 'table_name'}
				
			   ]
		     )
		})
	var combotable = new Ext.form.ComboBox({
		fieldLabel:'Buscar',
  		xtype:'combo',
  		store:stcombo,
		valueField:'table_name',
		displayField:'table_name',
        triggerAction: 'all',
        editable: false,
        mode: 'local',
        emptyText: '[-Seleccione-]',
        anchor:'50%',
        listeners:{select:onclickcombo}
})
	stcombo.load();
	function onclickcombo(){
		fecha.reset();fecha.disable();
		Ext.getBody().mask('Cargando configuracion y datos...');
		Ext.Ajax.request({
			url: 'configrid',
			method:'POST',
			params:{table:combotable.getValue()},
			callback: function (options,success,response){
								responseData = Ext.decode(response.responseText);
								if(responseData.grid)
									
									var newcm = Ext.UCID.generaDinamico('cm', responseData.grid.columns);
									storegrid = new Ext.data.Store({
									url : 'cargargriddatos',
									listeners : {'load' : function() {gdGestionHis.getSelectionModel().selectFirstRow()}
									},
										reader : new Ext.data.JsonReader({
										totalProperty: 'cantidad',
										root : 'datos',
										id : 'id_historial'
									},Ext.UCID.generaDinamico('rdcampos', responseData.grid.campos))
								});
									Ext.getBody().unmask();
									if (newcm && storegrid)
									{				
										gdGestionHis.reconfigure(storegrid, newcm);
										gdGestionHis.getBottomToolbar().bind(storegrid);
										gdGestionHis.loadMask = new Ext.LoadMask(Ext.getBody(), {msg : 'cargando...', store : storegrid});
										storegrid.baseParams = {table:combotable.getValue()};
										storegrid.load({params : {start : 0,limit : 10}});
									}
									combotable.clearValue();
									fecha.enable();
			}
			
			
	});
}

	smgestion = new Ext.grid.RowSelectionModel({
		singleSelect : true,
		listeners : {
			'rowselect' : function(smodel, rowIndex, keepExisting, record) {
				
			}
		}
	})
	cmGestionhist = new Ext.grid.ColumnModel([{
		id : 'expandir',
		autoExpandColumn : 'expandir'
	}]);
	gdGestionHis = new xg.GridPanel({
		frame : true,
		sm : smgestion,
		store : storegrid,
		loadMask : {
			store : storegrid
		},
		cm : cmGestionhist,
		tbar : [btnRollback,"  ",fecha,"  ","Tablas:","  ",combotable],
		bbar : new Ext.PagingToolbar({
			store : storegrid,
			displayInfo : true,
			pageSize : 10
			
		})
	});
	view = new Ext.Viewport({
		layout:'fit',
		items: gdGestionHis
	})
	}
cargarInterfaz();	
function rollback(){
alert ("rollback");
}
