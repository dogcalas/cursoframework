Ext.QuickTips.init();
var perfil = window.parent.UCID.portal.perfil
perfil.etiquetas = Object();
// UCID.portal.cargarEtiquetas('gestionarnomina',cargarInterfaz);
var view, panel, ghistory, ghistorial;
var stghistory, stghistorial, stcombo, smhistory, smhistorial;
var array = [];
var eliminados = [];
var btnAyuda = new Ext.Button({
	handler : function() {
	},
	text : 'Ayuda',
	id : 'btnAyu',
	icon : perfil.dirImg + 'ayuda.png',
	iconCls : 'btn'
});
var btnAdicionar = new Ext.Button({
	handler : crearhistorial,
	text : 'Crear historial',
	disabled : true,
	id : 'btnAdd',
	icon : perfil.dirImg + 'adicionar.png',
	iconCls : 'btn'
});
var btnEliminar = new Ext.Button({
	handler : eliminarhistorial,
	text : 'Eliminar historial',
	disabled : true,
	id : 'btnAdd',
	icon : perfil.dirImg + 'eliminar.png',
	iconCls : 'btn'
});

function cargarInterfaz() {
	/* store ghistory */
	stghistory = new Ext.data.Store({
		url : 'inicial',
		listeners : {
			'load' : function() {
			}
		},
		reader : new Ext.data.JsonReader({
			totalProperty : "total",
			root : "datos"
			
		}, [{
			name : 'table_name'
		}, {
			name : 'table_schema'
		}])
	});
	stghistorial = new Ext.data.Store({
		url : 'cargarhistorial',
		listeners : {
			'load' : function() {
			}
		},
		reader : new Ext.data.JsonReader({
			totalProperty : "total",
			root : "datos"
			
		}, [{
			name : 'table_name'
		}, {
			name : 'fecha'
		}, {
			name : 'id_historial'
		}, {
			name : 'creado'
		},{
			name : 'esquema'
		},{
			name : 'hora'
		}])
	});
	stcombo = new Ext.data.Store({
		url : 'cargarschema',
		listeners : {
			'load' : function() {
			}
		},
		reader : new Ext.data.JsonReader({
			root : "datos"			
		}, [{
			name : 'table_schema'
		}

		])
	})
	var comboschema = new Ext.form.ComboBox({
		xtype : 'combo',
		store : stcombo,
		valueField : 'table_schema',
		displayField : 'table_schema',
		triggerAction : 'all',
		editable : false,
		mode : 'local',
		emptyText : '[-Seleccione-]',
		anchor : '40%',
		listeners : {
			select : onclickcombo
		}
	})
	stghistory.baseParams = {
		schema : comboschema.getValue()
	};
	stcombo.load();
	function onclickcombo() {
		stghistory.baseParams = {
			schema : comboschema.getValue()
		};
		stghistory.load({
			params : {
				start : 0,
				limit : 10
			}
		});
		if (comboschema.getValue() == 'todo')
			comboschema.clearValue();
	}
	smhistorial = new Ext.grid.RowSelectionModel({
		listeners : {
			rowselect : onSMclientesRowSelect1
		}
	})
	function onSMclientesRowSelect1(sm, i, rec) {
		btnEliminar.enable();
		eliminados.push(rec.data);
	}
	smhistory = new Ext.grid.RowSelectionModel({
		listeners : {
			rowselect : onSMclientesRowSelect
		}
	})
	function onSMclientesRowSelect(sm, i, rec) {
		btnAdicionar.enable();
		array.push(rec.data);
	}
	/* Grid */
	ghistory = new Ext.grid.GridPanel({
		title : 'Gestión de historial',
		width : 100,
		region : 'center',
		frame : true,
		// autoExpandColumn : 'expandir',
		store : stghistory,
		margins : '2 2 2 -4',
		sm : smhistory,
		columns : [{
			id : 'expandir',
			header : 'Tabla',
			width : 185,
			dataIndex : 'table_name'
		}, {
			header : 'Esquema',
			width : 185,
			dataIndex : 'table_schema'
		}],
		loadMask : {
			store : stghistory
		},
		tbar : [btnAdicionar," ", "Esquema: "," ",comboschema],
		bbar : new Ext.PagingToolbar({
			pageSize : 10,
			store : stghistory,
			displayInfo : true
		})
	});

	/* historial grid */
	ghistorial = new Ext.grid.GridPanel({
		title : 'Historial',
		autoExpandColumn : 'expandir',
		width : 650,
		region : 'east',
		frame : true,
		store : stghistorial,
		margins : '2 2 2 -4',
		sm : smhistorial,
		columns : [{
			hidden : true,
			hideable : false,
			dataIndex : 'id_historial'
		},{
			hidden : true,
			hideable : false,
			dataIndex : 'esquema'
		}, {
			id : 'expandir',
			header : 'Tabla',
			width : 175,
			dataIndex : 'table_name'
		}, {
			header : 'Fecha',
			width : 175,
			dataIndex : 'fecha'
		}, {
			header : 'Creado por',
			width : 175,
			dataIndex : 'creado'
		}, {
			header : 'Hora',
			width : 75,
			dataIndex : 'hora'
		}],
		loadMask : {
			store : stghistorial
		},
		tbar : [btnEliminar],
		bbar : new Ext.PagingToolbar({
			pageSize : 10,
			store : stghistorial,
			displayInfo : true
		})

	})
	panel = new Ext.Panel({
		layout : 'border',
		items : [ghistory, ghistorial]
	})
	/* Viewport */
	view = new Ext.Viewport({
		layout : 'fit',
		items : panel
	})

	stghistory.load({
		params : {
			start : 0,
			limit : 10
		}
	});
	stghistorial.load({
		params : {
			start : 0,
			limit : 10
		}
	});
}
cargarInterfaz();
function crearhistorial() {
	var datos = Ext.encode(array);
	array = [];
	Ext.Ajax.request({
		url : 'crearhistorial',
		method : 'POST',
		params : {
			datos : datos
		},
		callback : function(options, success, response) {
			responseData = Ext.decode(response.responseText);
			stghistorial.reload();
			mostrarMensaje(responseData.codMsg, responseData.mensaje);
			btnAdicionar.disable();
		}
	});
}
function eliminarhistorial() {
	var datos = Ext.encode(eliminados);
	eliminados = [];
	Ext.Ajax.request({
		url : 'eliminarhistorial',
		method : 'POST',
		params : {
			datos : datos
		},
		callback : function(options, success, response) {
			responseData = Ext.decode(response.responseText);
			stghistorial.reload();
			mostrarMensaje(responseData.codMsg, responseData.mensaje);
			btnEliminar.disable();
		}
	});
}