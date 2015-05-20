var perfil = window.parent.UCID.portal.perfil;
// 2.
//UCID.portal.cargarEtiquetas('gestionarnivel', perfil.idioma, function(){cargarInterfaz();});
// 3. Inicializo el singlenton QuickTips
Ext.QuickTips.init();


function cargarInterfaz()
	{
		Ext.onReady(function(){
			// buscar los tipos de datos a trabajar
			var stTipos= new Ext.data.Store({
							autoLoad: true,
    						proxy: new Ext.data.HttpProxy({	url: 'mostrartiposdatos'}),
    			 			reader: new Ext.data.JsonReader({
        								totalProperty: "cant",
        								root: "datos",
        								id: "id" 
        								},[{name: 'id',
        									mapping: 'id'
        								 },{
        								 	name: 'tipo',
        								 	mapping: 'tipo'
        								 }]
        					) 
        	});	
var Datos=[['1', 'Si'],['0', 'No']];
var stVisible= new Ext.data.SimpleStore({
        fields: ['valor', 'bol'],
        data : Datos// from states.js
    });

var stCampos= new Ext.data.Store({
    autoLoad: true,
    proxy: new Ext.data.HttpProxy({
        url: 'mostrartiposcampos'
    }),
    reader: new Ext.data.JsonReader({
        totalProperty: "cant",
        root: "datos",
        id: "id"    
    } ,   
    [{
        name: 'id',
        mapping: 'id'
    }, {
        name: 'tipo',
        mapping: 'tipo'
    }] 
	)
       
       
});	

var vGestionTablas; var vGestionCampos; var vGestionFPCampos;
//Formulario Gestion de tablas
var fpGestionTablas = new Ext.FormPanel({
	labelAlign: 'top',
	url:'mostrartablas',
	frame:true,
	border:'false',
	items:[{
		layout:'column',
		style:'margin:10 0 0 10',
		items:[{
			columnWidth:1,
			layout: 'form',
			items: [{
					xtype:'textfield',
					fieldLabel: 'Nombre',
					id: 'nombre',
					anchor:'97.5%'
			}]
		},{
			columnWidth:.5,
			layout: 'form',
			items: [{
				xtype:'datefield',
				fieldLabel: 'Fecha inicio',
				id: 'fechaini',
				anchor:'95%'
			}]
		},{
			columnWidth:.5,
			layout: 'form',
			items: [{
				xtype:'datefield',
				fieldLabel: 'Fecha fin',
				id: 'fechafin',
				anchor:'95%'
			}]
		}]
	}]
});
//Formulario Gestion de campos
var fpGestionCampos = new Ext.FormPanel({
	labelAlign: 'top',
	url:'mostrarcampos',
	frame:true,
	border:'false',
	items:[{
		layout:'column',
		style:'margin:5 0 0 10',
		items:[{
			columnWidth:.32,
			layout: 'form',
			items: [{
					xtype:'textfield',
					fieldLabel: 'Nombre',
					id: 'nombre',
					anchor:'93%'
			},{
					xtype:'combo',
					fieldLabel: 'Tipo dato',
					store:stTipos,
					valueField:'id',
					displayField:'tipo',
					hideLabel:false,
					hiddenName:'tipoDato',
					autoCreate: true,
					mode: 'local',
					forceSelection: true,
					anchor:'93%'
			},{
					xtype:'textfield',
					fieldLabel: 'Reg Exp',
					id: 'regex',
					anchor:'93%'
			}]
		},{
			columnWidth:.32,
			layout: 'form',
			items: [{
				xtype:'textfield',
				fieldLabel: 'Alias',
				id: 'nombrec',
				anchor:'93%'
			},{
					xtype:'combo',
					fieldLabel: 'Tipo campo',					
					store:stCampos,
					valueField:'id',
					displayField:'tipo',
					hideLabel:false,
					hiddenName:'tipoCampo',
					autoCreate: true,
					mode: 'local',
					forceSelection: true,
					anchor:'93%'
			},{
					xtype:'combo',
					fieldLabel: 'Visible',
					store:stVisible,
					hideLabel:false,
					hiddenName:'Visible',
					mode: 'local',
					valueField:'valor',
					displayField:'bol',
					id: 'visible',					
					anchor:'93%'  
			}]
		},{
			columnWidth:.32,
			layout: 'form',
			items: [{
				xtype:'numberfield',
				fieldLabel: 'Longitud',
				id: 'longitud',
				anchor:'100%'
			},{
					xtype:'textarea',
					height:70,
					fieldLabel: 'Descripci&oacute;n',
					id: 'descripcion',
					anchor:'100%'
			}]
		}]
	}]
});
//--------->Especificacion de la forma de seleccion de las filas del grid
var modoSeleccionTabla = new Ext.grid.RowSelectionModel({
     singleSelect:true
	 });
	 
	 
//--------->EVENTO PARA ACTIVAR LSO BOTONES
modoSeleccionTabla.on("rowselect",function(_sm, indiceFila, record){

	
   btnAdicionar.enable();
   btnModificar.enable();
   btnEliminar.enable();
   btnGestionarcampos.enable();
   


})	 
	 

//--------->Especificacion de la forma de seleccion de las filas del grid
var modoSeleccionCampo = new Ext.grid.RowSelectionModel({
     singleSelect:true
	 });

 
//--------->EVENTOS DEL GRID; SE PRODUCE CUANDO SE SELECCIONA UNA FILA

modoSeleccionCampo.on("selectionchange", function(_sm, indiceFila, record){
  if(_sm.getSelections().length>0)
  {
  
   

 /*  btnModificar.enable();   
    btnEliminar.enable();    */
   
}}); 

//Store grid Gestion de Tablas
//Botones del Grid de la gestion de Tablas
var btnAdicionar=new Ext.Button( {
			icon:'../images/icon/agregar.png',
			id:'btnAdicionar',
			iconCls:'btn',
			handler:function(){mostrarVGestionTablas();},
			text:'Adicionar',
			disabled:true
	});
	
var btnModificar=new Ext.Button({	icon:'../images/icon/modificar.png',
	        id:'btnModificar',
			iconCls:'btn',
			handler:function(){mostrarVGestionTablas();},
			text:'Modificar',
			disabled:true
	})	
	
var btnEliminar=new Ext.Button({	icon:'../images/icon/eliminar.png',
			id:'btnEliminar',
			iconCls:'btn',
			handler:function(){eliminarTabla();},
			text:'Eliminar',
			disabled:true
	})

var btnGestionarcampos=new Ext.Button({	icon:'../images/icon/visualizar.png',
			iconCls:'btn',
			id:'btnGestionarcampos',
			handler:function(){stGestionCampos.load({params:{start:0,limit:20,idtabla:modoSeleccionTabla.getSelected().data.idnomeav}});mostrarVGestionCampos();},
			text:'Gestionar Campos',
			disabled:true
	}	)
	/*
	 * Store del Grid que muestra los tipos de metadatos
	 */
var stGestionTablas =  new Ext.data.Store({
							baseParams:{accion:'CargarDatosCU'},
							url: 'mostrartablas',
							reader:new  Ext.data.JsonReader({
										root:'datos',
										id:'nombre',
										totalProperty:'totalRecords'
									},[{
										name: 'nombre'
									 },{
									 	name: 'fechaini'
									 },{
									 	name: 'fechafin'
									 },{
									 	name: 'idnomeav'
									   }]
							)
					});
//Grid Gestion de Tablas
var gdGestionTablas = new Ext.grid.GridPanel({
	title:'Gestion de Tablas',
	frame:true,
	iconCls:'icon-grid',
	autoExpandColumn:'expandir',
	store:stGestionTablas,
	sm:modoSeleccionTabla,
	columns: [
		{id:'expandir',header: 'Nombre', dataIndex: 'nombre'},
		{header: 'Fecha de inicio', width:100, dataIndex: 'fechaini'},
		{header: 'Fecha de fin', width:100, dataIndex: 'fechafin'}
	],
	tbar:[btnAdicionar,btnModificar,btnEliminar,btnGestionarcampos],
	bbar: new Ext.PagingToolbar({
		pageSize: 5,
		store: stGestionTablas,
		displayInfo: true,
		displayMsg: 'Resultados de {0} - {1} de {2}',
		emptyMsg: "No hay resultados para mostrar."
	})
});
//Store grid Gestion de Campos
var stGestionCampos =  new Ext.data.Store({
	url: 'mostrarcampos',
	
	reader:new Ext.data.JsonReader({
		root:'datos',
		id:'nombre',
		totalProperty:'totalRecords'
		},
		[	{name: 'nombre'},
			{name: 'nombre_mostrar'},
			{name: 'longitud'},
			{name: 'tipo'},
			{name: 'tipocampo'},
			{name: 'visible'},
			{name: 'regex'},
			{name: 'descripcion'},
			{name: 'idcampo'} ]
	)
});
//Grid Gestion de Campos
var gdGestionCampos = new Ext.grid.GridPanel({
	frame:true,
	iconCls:'icon-grid',
	autoExpandColumn:'expandir',
	store:stGestionCampos,
	sm:modoSeleccionCampo,
	columns: [
		{id:'expandir',header: 'Nombre', dataIndex: 'nombre'},
		{header: 'Alias', width:80, dataIndex: 'nombre_mostrar'},
		{header: 'Longitud', width:55, dataIndex: 'longitud'},
		{header: 'Tipo dato', width:60, dataIndex: 'tipo'},
		{header: 'Tipo campo', width:70, dataIndex: 'tipocampo'},
		{header: 'Visible?', width:55, dataIndex: 'visible'},
		{header: 'Reg Exp', width:55, dataIndex: 'regex'},
		{header: 'Descripcion', width:100, dataIndex: 'descripcion'}
	],
	tbar:[{
			icon:'../images/icon/agregar.png',
			iconCls:'btn',
			handler:function(){mostrarVGestionFPCampos();},
			text:'Adicionar'
	},{	icon:'../images/icon/modificar.png',
			iconCls:'btn',
			handler:function(){mostrarVGestionFPCampos();},
			text:'Modificar'
	},{	icon:'../images/icon/eliminar.png',
			iconCls:'btn',
			handler:function(){eliminarCampo();},
			text:'Eliminar'
	},{	icon:'../images/icon/ayuda.png',
			iconCls:'btn',
			handler:function(){},
			text:'Ayuda'
	}],
	bbar: new Ext.PagingToolbar({
		pageSize: 5,
		store: stGestionCampos,
		displayInfo: true,
		displayMsg: 'Resultados de {0} - {1} de {2}',
		emptyMsg: "No hay resultados para mostrar."
	})
});
//Ventana Formulatio de Gestion de Tablas
function mostrarVGestionTablas(){
	if(!vGestionTablas){
		vGestionTablas = new Ext.Window({
			title:'Gestion Tablas',
			layout:'fit',
			width:400,
			modal:true,
			items:fpGestionTablas,
			height:200,
			closeAction:'hide',
			buttons:[{
						icon:'../images/icon/cancelar.png',
						iconCls:'btn',
						handler:function(){vGestionTablas.hide();},
						text:'Cancelar'
				},{	icon:'../images/icon/aceptar.png',
						iconCls:'btn',
						handler:function(){adicionarEAV();},
						text:'Aceptar'
				}]
		});
	}
	vGestionTablas.show(this);
}

function adicionarEAV(apl){
	if (fpGestionTablas.getForm().isValid()){
		fpGestionTablas.getForm().submit({
			url:'insertatablas',
			waitMsg:'Registrando tabla...',
			//params:{idpadre:nodoSeleccionado.id},
			failure: function(form, action){
				mostrarMensaje(action.result.codMsg,action.result.mensaje);
				if(action.result.codMsg != 3){ 
					fpGestionTablas.getForm().reset(); 
					if(!apl) vGestionTablas.hide();
				}
				stGestionTablas.reload();
			}
		});
		}
}

function adicionarCamposEAV(apl){
	if (fpGestionCampos.getForm().isValid()){
		fpGestionCampos.getForm().submit({
			url:'insertacampos',
			//waitMsg:'Registrando Entidad...',
			//params:{idpadre:nodoSeleccionado.id},
			params:{idtabla:modoSeleccionTabla.getSelected().data.idnomeav},
			failure: function(form, action){
				//alert(modoSeleccionTabla.getSelected().data.idnomeav);
				
				mostrarMensaje(action.result.codMsg,action.result.mensaje);
				if(action.result.codMsg != 3){ 
					fpGestionCampos.getForm().reset(); 
					if(!apl) vGestionFPCampos.hide();
				}
				 
				stGestionCampos.reload();
			}
		});
		}
}

function eliminarTabla(){
	if(!modoSeleccionTabla.getSelected())
		mostrarMensaje(1,'Debe seleccionar el Nivel 1 que desea eliminar');
	else{
		mostrarMensaje(2,'Est&aacute; seguro que desea eliminar al nivel '+modoSeleccionTabla.getSelected().get('nombre')+' ?',elimina)
	}
	function elimina(btnPresionado){
		if (btnPresionado == 'ok'){
			Ext.Ajax.request({
				url: 'eliminartabla',
				method:'POST',
				params:{idtabla:modoSeleccionTabla.getSelected().data.idnomeav},
				callback: function (options,success,response){
						responseData = Ext.decode(response.responseText);
						mostrarMensaje(responseData.codMsg,responseData.mensaje);
						stGestionTablas.reload();
				}
			});
		}
	}
}	

function eliminarCampo(){
	if(!modoSeleccionCampo.getSelected())
		mostrarMensaje(1,'Debe seleccionar el campo que desea eliminar');
	else{
		mostrarMensaje(2,'Est&aacute; seguro que desea eliminar el campo "'+modoSeleccionCampo.getSelected().get('nombre')+'" ?',elimina)
	}
	function elimina(btnPresionado){
		if (btnPresionado == 'ok'){
			Ext.Ajax.request({
				url: 'eliminarcampos',
				method:'POST',
				params:{idcampo:modoSeleccionCampo.getSelected().data.idcampo},
				callback: function (options,success,response){
						responseData = Ext.decode(response.responseText);
						mostrarMensaje(responseData.codMsg,responseData.mensaje);
						stGestionCampos.reload();
				}
			});
		}
	}
}	

// Ventana auxiliar de mensajes
function mostrarMensaje(tipo, msg, fn){
    var buttons = new Array(Ext.MessageBox.OK, Ext.MessageBox.OKCANCEL, Ext.MessageBox.OK);
    var title = new Array('Informaci&oacute;n', 'Confirmaci&oacute;n', 'Error');
    var icons = new Array(Ext.MessageBox.INFO, Ext.MessageBox.QUESTION, Ext.MessageBox.ERROR);
    Ext.MessageBox.show({
        title: title[tipo - 1],
        msg: msg,
        buttons: buttons[tipo - 1],
        icon: icons[tipo - 1],
        fn: fn
    });
}

//Ventana grid Gestion de Campos
function mostrarVGestionCampos(){
	if(!vGestionCampos){
		vGestionCampos = new Ext.Window({
			title:'Gestionar Campos',
			layout:'fit',
			width:600,
			modal:true,
			items:gdGestionCampos,
			height:300,
			closeAction:'hide',
			buttons:[{
						icon:'../images/icon/cancelar.png',
						iconCls:'btn',
						handler:function(){vGestionCampos.hide();},
						text:'Cancelar'
				},{	icon:'../images/icon/aceptar.png',
						iconCls:'btn',
						handler:function(){},
						text:'Aceptar'
				}]
		});
	}
	vGestionCampos.show(this);
}
//Ventana Formulario gestion de campos
function mostrarVGestionFPCampos(){
	if(!vGestionFPCampos){
		vGestionFPCampos = new Ext.Window({
			title:'Adicionar Campo',
			layout:'fit',
			width:450,
			modal:true,
			items:fpGestionCampos,
			height:230,
			closeAction:'hide',
			buttons:[{
						icon:'../images/icon/cancelar.png',
						iconCls:'btn',
						handler:function(){vGestionFPCampos.hide();},
						text:'Cancelar'
				},{	icon:'../images/icon/aceptar.png',
						iconCls:'btn',
						handler:function(){adicionarCamposEAV();},
						text:'Aceptar'
				}]
		});
	}
	vGestionFPCampos.show(this);
}
//ViwePort ***/
var vpGestionTablas = new Ext.Viewport({
	layout:'fit',
	items:gdGestionTablas
});
stGestionTablas.load({params:{start:0,limit:20}});
})}cargarInterfaz()