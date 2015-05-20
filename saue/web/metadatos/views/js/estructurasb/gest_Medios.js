/**
 * @author David Martinez Alarcon
 * @class GestionarCargo
 */
GestionarMedios = function(){
	 /**VARIABLES PRIVADAS*/
	//var fun_sel,id,wn_GestionarPuestos,wn_FormCargos,rec;
	var fun_sel,id,wn_GestionarMedios,wn_FormCargos,rec;

	var columns= [{
					id:'expandir',header: 'Denominación', dataIndex: 'dentecnica'
				},{
					header: 'Abreviatura', dataIndex: 'abrevtecnica'
				},{
					header: 'Código', dataIndex: 'codtecnica'
				}]; 

	var st_MostrarTecnica =  new Ext.data.Store({
										url:'mostrartecnica',
										reader:new Ext.data.JsonReader({
											root:'datos',
											id:'idtecnica',
											totalProperty:'cant'
											},
											[{
												name: 'idtecnica'
											},{
												name:'dentecnica', mapping:'dentecnica'
											},{
												name:'abrevtecnica', mapping:'abrevtecnica'
											},{	
												name:'codtecnica', mapping:'codtecnica'
											}/*,{
												name:'cantidad', mapping:'DatTecnica.cantidad'
											}*/]
										)		
						});						
						
	var st_MostrarnTecnica =  new Ext.data.Store({
										autoLoad: true,
										url:'mostrarntecnica',
										reader:new Ext.data.JsonReader({
											root:'datos',
											id:'idtecnica',
											totalProperty:'cant'
											},
											[{
												name: 'idtecnica'
											},{
												name:'dentecnica', mapping:'dentecnica'
											}]
										)		
						});						
						
						
	var sm_MostrarTecnica = new Ext.grid.RowSelectionModel({singleSelect:true});
	
	
	
	var bt_AdicionarTecnica = new Ext.Button({
								  text:'Adicionar',
								  icon:perfil.dirImg+'adicionar.png',
								  iconCls:'btn',
								  id:"AdicionarTecnica",
								  handler:function(){
										fun_sel = this.id;
										Onbt_AdicionarTecnicaClick();										
									}									
			});
	var bt_ModificarTecnica = new Ext.Button({
								text:'Modificar',
								icon:perfil.dirImg+'modificar.png',
								iconCls:'btn',
								id:"ModificarTecnica",
								disabled:true,										
								handler:function(){
										fun_sel= this.id;
										Onbt_ModificarTecnicaClick();										
									}									
						});
	var bt_EliminarTecnica = new Ext.Button({
										text:'Eliminar',
										icon:perfil.dirImg+'eliminar.png',
										iconCls:'btn',
										id:"EliminarTecnica",
										disabled:true,											
										handler:function(){eliminarTecnica();}									
								});
	
	var bt_AyudaTecnica = new Ext.Button({
										icon:perfil.dirImg+'ayuda.png',
										iconCls:'btn',
										id:"Ayuda",
										handler:function(){}									
								});
	var gd_MostrarTecnica = new Ext.grid.GridPanel({
							frame:true,
							iconCls:'icon-grid',
							autoExpandColumn:'expandir',
							store:st_MostrarTecnica,
							sm:sm_MostrarTecnica,
							viewConfig :{forceFit :true},
							loadMask:{store:st_MostrarTecnica},
							columns: columns,
							tbar:[bt_AdicionarTecnica,bt_ModificarTecnica,bt_EliminarTecnica,'-',bt_AyudaTecnica],
							bbar: new Ext.PagingToolbar({
											pageSize: 10,
											store: st_MostrarTecnica,
											displayInfo: true
								})
						});
	var nf_CTG = new Ext.form.NumberField({		 
						fieldLabel: 'CTG',
						maxLength:3,
						maxLengthText:'El máximo de caracteres es 3',
						id: 'ctg',
						name:'ctg',
						regex:/^\d*$/,               // /^([a-zA-Zï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½\d]+ ?[a-zA-Zï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½\d]*)+$/,
						regexText:'Valor incorrecto',
						//allowBlank:false,
						//blankText:'Este campo es requerido.',
						anchor:'93%'
						});					
	var col1_FormTecnicas = {
		        layout: 'form',		
			    defaultType:'combo',
			    items:[{
				fieldLabel: 'Medios',	
				labelalign:'top',
				id:"idcbtecnica",
				editable :false,
				triggerAction:'all',
				forceSelection:true,
				emptyText:'Seleccione ...',					
				autoCreate: true,
				mode: 'local',
				anchor:'93%',
				store:st_MostrarnTecnica,
				displayField:'dentecnica',
				valueField:'idtecnica',
				hiddenName:'idtecnica'
			}, { 
				xtype:'numberfield',
                blankText:'Este campo es requerido',
				maskRe:/^\d*$/,
				regex:/^\d*$/,
			    regexText:'Valor incorrecto',
                labelalign:'top',
                fieldLabel: 'Cantidad',
                name: 'cantidad',
                allowBlank:false
            },nf_CTG]
     };
	
	var fm_Tecnicas = new Ext.FormPanel({
							labelAlign: 'top',
							frame:true,
							border:'false',
							items:[{
								layout:'column',
								style:'margin:5 0 0 10',
								items:[col1_FormTecnicas]
							}]
		});	
	/**VARIABLES PUBLICAS*/
	this.id;
	this.title_WinGestionarTecnica;
	
	/**FUNCIONES PRIVADAS*/
	sm_MostrarTecnica.on("rowselect",function(_sm, indiceFila,record){	
   					rec = record;
   					SetEstadoBotonesTbarGrid();
   				});
   				
   	Onbt_AdicionarTecnicaClick = function(){
   			//LimpiarCampos_FormPuestos();
			mostrarWin_FormTecnica();
		}
	
   	Onbt_ModificarTecnicaClick = function(){
   		mostrarWin_FormTecnica();
   		//console.info(rec);
   		fm_Tecnicas.getForm().loadRecord(rec);
   		//CargarCampos_FormPuestos();
   		
   		}
   	/*CargarCampos_FormPuestos = function (){
   			fm_Puestos.findById('denominacion').setValue(DameSeleccionadoPuestos("denominacion"));
			fm_Puestos.findById('abreviatura').setValue(DameSeleccionadoPuestos("abreviatura"));
			fm_Puestos.findById('habilidades').setValue(DameSeleccionadoPuestos("habilidades"));
			fm_Puestos.findById('condiciones').setValue(DameSeleccionadoPuestos("condiciones"));
			fm_Tecnicas.findById('acciones').setValue(DameSeleccionadoPuestos("acciones"));
			fm_Tecnicas.findById('riesgos').setValue(DameSeleccionadoPuestos("riesgos"));
		}*/
   		
   	DameSeleccionadaTecnica= function(idt){
				var params ="";
					if(TieneTecnicaSeleccionada())
						{
							params = sm_MostrarTecnica.getSelected().get(idt);
						}
						//alert(params);
					return params;
				}
   	TieneTecnicaSeleccionada = function(){
   		return (sm_MostrarTecnica.hasSelection());
   		}
   		
	mostrarWin_FormTecnica = function (){
		if(!wn_FormCargos){	
						wn_FormCargos = new Ext.Window({
											title:'Confeccionar técnica',
											layout:'fit',
											width:180,
											height:200,
											modal:true,
											items:fm_Tecnicas,							
											closeAction:'hide',
											buttons:
											[{
												text:'Cancelar',
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												handler:function(){
															fm_Tecnicas.getForm().reset();
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
			if(TieneTecnicaSeleccionada()){
					bt_ModificarTecnica.enable();
					bt_EliminarTecnica.enable();
				}
			else{
					bt_ModificarTecnica.disable();
					bt_EliminarTecnica.disable();
				}
		}
	
	OnAceptarClick = function(){		
			if(fun_sel == 'AdicionarTecnica'){//si se selecciono adicionar o modificar
					sbmt_EnviarfmTecnica('insertartecnica',{idcargo:id })
				}
			else if(fun_sel == 'ModificarTecnica'){				
				sbmt_EnviarfmTecnica('modificartecnica',{idcargo:id})
				}
		}
		
	
		
	sbmt_EnviarfmTecnica = function(url,params){
					if (fm_Tecnicas.getForm().isValid())
						{
									fm_Tecnicas.getForm().submit({
									url:url,
									params:params,	
									failure: function(form, action){										
										mostrarMensaje(action.result.codMsg,action.result.mensaje);	
										if(action.result.codMsg != 3)
											{											
												fm_Tecnicas.getForm().reset();
												wn_FormCargos.hide();
												st_MostrarTecnica.reload();
										}
									
									}
								,waitMsg:'Enviando los datos...'});
							
						}
					else{ 
							mostrarMensaje(3,'Por favor verifique nuevamente que hay campo(s) con valor(es) incorecto(s).');
					}
				};
	eliminarTecnica = function(){				
					if(!TieneTecnicaSeleccionada())
						mostrarMensaje(1,'Debe seleccionar la tecnica que desea eliminar.');
					else{
						mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar '+DameSeleccionadaTecnica("dentecnica")+' ?',elimina)
					}
					function elimina(btnPresionado){
							if (btnPresionado == 'ok'){
									Ext.Ajax.request({
											url: 'eliminartecnica',
											method:'POST',
											params:{idtecnica:DameSeleccionadaTecnica("idtecnica"),idcargo:id},
											callback: function (options,success,response){
																responseData = Ext.decode(response.responseText);
																mostrarMensaje(responseData.codMsg,responseData.mensaje);
																st_MostrarTecnica.reload();
																sm_MostrarTecnica.clearSelections();
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
	this.mostrarWin_GestTecnicas = function (){
				id = this.id;
				//id=id.substr(1,2);
				var tecnica = this.title_WinGestionarTecnica;
				st_MostrarTecnica.baseParams = {idestructura:id };
				st_MostrarTecnica.load({params:{start:0,limit:10}});
				//st_MostrarnTecnica.load();
				if(!wn_GestionarMedios){						
							wn_GestionarMedios = new Ext.Window({
												title:'Gestionar medios',
												layout:'fit',
												width:600,
												modal:true,
												height:300,
												closeAction:'hide',
												items:gd_MostrarTecnica
											});
					
					}
				SetEstadoBotonesTbarGrid();
				wn_GestionarMedios.setTitle(tecnica);
				wn_GestionarMedios.show(this);
				}
	
	
	
	}