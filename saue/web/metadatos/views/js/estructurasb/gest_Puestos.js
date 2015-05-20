/**
 * @author David Martinez Alarcon
 * @class GestionarCargo
 */
GestionarPuestos = function(){
	 /**VARIABLES PRIVADAS*/
	var fun_sel,id,wn_GestionarPuestos,wn_FormCargos,rec;
	
	var st_MostrarPuestos =  new Ext.data.Store({
										url:'mostrarpuestos',
										reader:new Ext.data.JsonReader({
											root:'datos',
											id:'idpuesto',
											totalProperty:'cant'
											},
											[
											{name: 'idpuesto'},
											{name: 'denominacion', mapping:'denominacion'},
											{name: 'abreviatura'},
											{name: 'habilidades'},
											{name: 'acciones'},											
											{name: 'riesgos'},
											{name: 'condiciones'}											
											]
										)		
						});
	var sm_MostrarPuestos = new Ext.grid.RowSelectionModel({singleSelect:true});
	
	var columns= [
				{id:'expandir',header: 'Puesto', dataIndex: 'denominacion'},
				{header: 'Abreviatura', width:50, dataIndex: 'abreviatura'},
				{header: 'Habilidades', width:40, dataIndex: 'habilidades'},
				{header: 'Condiciones', width:40, dataIndex: 'condiciones'},
				{header: 'Acciones', width:60, dataIndex: 'acciones'},
				{header: 'Riesgos', width:40, dataIndex: 'riesgos'}			
	]; 
	var bt_AdicionarPuestos = new Ext.Button({
								  text:'Adicionar',
								  icon:perfil.dirImg+'adicionar.png',
								  iconCls:'btn',
								  id:"Adicionarpsto",
								  handler:function(){
										fun_sel = this.id;
										Onbt_AdicionarPuestosClick();										
									}									
			});
	var bt_ModificarPuestos = new Ext.Button({
								text:'Modificar',
								icon:perfil.dirImg+'modificar.png',
								iconCls:'btn',
								id:"Modificarpsto",
								disabled:true,										
								handler:function(){
										fun_sel= this.id;
										Onbt_ModificarPuestosClick();
										//Llenar_FormPuestos();
										//QuitarCampos();
										//CrearForma();
										//LlenarCamposForma();
										//mostrarWin_FormPuestos();
									}									
						});
	var bt_EliminarPuestos = new Ext.Button({
										text:'Eliminar',
										icon:perfil.dirImg+'eliminar.png',
										iconCls:'btn',
										id:"Eliminarpsto",
										disabled:true,											
										handler:function(){eliminarPuestos();}									
								});
	
	var bt_AyudaPuestos = new Ext.Button({
										icon:perfil.dirImg+'ayuda.png',
										iconCls:'btn',
										id:"Ayuda",
										handler:function(){}									
								});
	var gd_MostrarPuestos = new Ext.grid.GridPanel({
							frame:true,
							iconCls:'icon-grid',
							autoExpandColumn:'expandir',
							store:st_MostrarPuestos,
							sm:sm_MostrarPuestos,
							viewConfig :{forceFit :true},
							loadMask:{store:st_MostrarPuestos},
							columns: columns,
							tbar:[bt_AdicionarPuestos,bt_ModificarPuestos,bt_EliminarPuestos,'-',bt_AyudaPuestos],
							bbar: new Ext.PagingToolbar({
											pageSize: 10,
											store: st_MostrarPuestos,
											displayInfo: true
											
								})
						});
	var col1_FormPuestos = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',	
						items: [{													
							fieldLabel: 'Puesto',
							name: 'denominacion',
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'//,
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
						},{
							fieldLabel: 'Abreviatura',
							name: 'abreviatura',
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'
						},{
							fieldLabel: 'Habilidades',
							name: 'habilidades',
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'//,
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/
								
						}]
					};
	var col2_FormPuestos = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{								
							fieldLabel: 'Condiciones',
							name: 'condiciones',							
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'//,							
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
						},{
							fieldLabel: 'Acciones',
							name: 'acciones',							
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'//,
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
						},{
							fieldLabel: 'Riesgos',
							name: 'riesgos',
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'//,
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/  //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,								
						}]
					};
	var fm_Puestos = new Ext.FormPanel({
							labelAlign: 'top',
							frame:true,
							border:'false',
							items:[{
								layout:'column',
								style:'margin:5 0 0 10',
								items:[col1_FormPuestos,col2_FormPuestos]
							}]
		});	
	/**VARIABLES PUBLICAS*/
	this.id;
	this.title_WinGestionarPuestos;
	
	/**FUNCIONES PRIVADAS*/
	sm_MostrarPuestos.on("rowselect",function(_sm, indiceFila, record){	
   					rec = record;
   					SetEstadoBotonesTbarGrid();
   				});
   				
   	Onbt_AdicionarPuestosClick = function(){
   			//LimpiarCampos_FormPuestos();
			mostrarWin_FormPuestos();
		}
	
   	Onbt_ModificarPuestosClick = function(){
   		mostrarWin_FormPuestos();
   		fm_Puestos.getForm().loadRecord(rec);
   		//CargarCampos_FormPuestos();
   		
   		}
   	/*CargarCampos_FormPuestos = function (){
   			fm_Puestos.findById('denominacion').setValue(DameSeleccionadoPuestos("denominacion"));
			fm_Puestos.findById('abreviatura').setValue(DameSeleccionadoPuestos("abreviatura"));
			fm_Puestos.findById('habilidades').setValue(DameSeleccionadoPuestos("habilidades"));
			fm_Puestos.findById('condiciones').setValue(DameSeleccionadoPuestos("condiciones"));
			fm_Puestos.findById('acciones').setValue(DameSeleccionadoPuestos("acciones"));
			fm_Puestos.findById('riesgos').setValue(DameSeleccionadoPuestos("riesgos"));
		}*/
   		
   	DameSeleccionadoPuestos= function(idp){
				var params ="";
					if(TienePuestosSeleccionados())
						{
							params = sm_MostrarPuestos.getSelected().get(idp);
						}
						//alert(params);
					return params;
				}
   	TienePuestosSeleccionados = function(){
   		return (sm_MostrarPuestos.hasSelection());
   		}
   		
	mostrarWin_FormPuestos = function (){
		if(!wn_FormCargos){	
						wn_FormCargos = new Ext.Window({
											title:'Confeccionar puestos',
											layout:'fit',
											width:400,
											height:250,
											modal:true,
											items:fm_Puestos,							
											closeAction:'hide',
											buttons:
											[{
												text:'Cancelar',
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												handler:function(){
															fm_Puestos.getForm().reset();
															wn_FormCargos.hide();
												}
											},{
												text:'Aceptar',
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												handler:function(){
												OnAceptarClick();
												}						
											}]
								});
			}
		wn_FormCargos.show(this);
		};
	
	
	SetEstadoBotonesTbarGrid = function(){
			if(TienePuestosSeleccionados()){
					bt_ModificarPuestos.enable();
					bt_EliminarPuestos.enable();
				}
			else{
					bt_ModificarPuestos.disable();
					bt_EliminarPuestos.disable();
				}
		}
	
	OnAceptarClick = function(){		
			if(fun_sel == 'Adicionarpsto'){//si se selecciono adicionar o modificar
					sbmt_EnviarfmPuestos('insertarpuesto',{idcargo:id })
				}
			else if(fun_sel == 'Modificarpsto'){				
				sbmt_EnviarfmPuestos('modificarpuesto',{idpuesto:DameSeleccionadoPuestos("idpuesto")})
				}
		}
		
	
		
	sbmt_EnviarfmPuestos = function(url,params){
					if (fm_Puestos.getForm().isValid())
						{
							fm_Puestos.getForm().submit({
									url:url,
									params:params,	
									failure: function(form, action){
											mostrarMensaje(action.result.codMsg,action.result.mensaje);
											if(action.result.codMsg != 3){
												st_MostrarPuestos.reload();
												fm_Puestos.getForm().reset();
												wn_FormCargos.hide();
											}
									}
								,waitMsg:'Enviando los datos...'});
							
						}
					else{ 
							mostrarMensaje(3,'Por favor verifique nuevamente que hay campo(s) con valor(es) incorecto(s).');
					}
				};
	eliminarPuestos = function(){				
					if(!TienePuestosSeleccionados())
						mostrarMensaje(1,'Debe seleccionar el  que desea eliminar.');
					else{
						mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar '+DameSeleccionadoPuestos("denominacion")+' ?',elimina)
					}
					function elimina(btnPresionado){
							if (btnPresionado == 'ok'){
									Ext.Ajax.request({
											url: 'eliminarpuesto',
											method:'POST',
											params:{idpuesto:DameSeleccionadoPuestos("idpuesto")},
											callback: function (options,success,response){
																responseData = Ext.decode(response.responseText);
																mostrarMensaje(responseData.codMsg,responseData.mensaje);
																st_MostrarPuestos.reload();
																sm_MostrarPuestos.clearSelections();
																SetEstadoBotonesTbarGrid();
											}
									});
							}
			
					}
				}
				
	mostrarMensaje = function(tipo, msg, fn){
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
	
	/**FUNCIONES PUBLICAS*/
	this.mostrarWin_GestPuestos = function (){
				id = this.id;
				var t = this.title_WinGestionarPuestos;
				st_MostrarPuestos.baseParams = {idcargo:id }
				st_MostrarPuestos.load({params:{start:0,limit:10}})
				//st_MostrarPuestos.reload();
				if(!wn_GestionarPuestos){						
							wn_GestionarPuestos = new Ext.Window({
												title:'Gestionar puestos',
												layout:'fit',
												width:600,
												modal:true,
												height:300,
												closeAction:'hide',
												items:gd_MostrarPuestos
											});
					
					}
				SetEstadoBotonesTbarGrid();
				wn_GestionarPuestos.setTitle(t);
				wn_GestionarPuestos.show(this);
				}
	
	
	
	}