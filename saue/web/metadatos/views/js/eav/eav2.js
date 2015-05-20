//-----------------------------d----------------------------------------------------------
gestionarEav  = function(){
	
	var grid_estructura; // usada para crear grid
	
	var sm_estructura,bbar_estructura;
	
	var col_estructura;
	
	var st_estructura;
	
	var url_tablas;
	
	var rf_estructura = "Tablas"; // guarda parte de la url
	
	var grid_relaciones,bbar_relaciones; // usada para crear grid
	
	var sm_relaciones;
		
	var col_relaciones;
	
	var rf_relaciones = "conexiones";
	
	var st_relaciones;
	
	var vp_GestionEav;
	
	var rec,recRe;
	
	var idtabla;
	 var title;
	
	var grid_habilitado = false;
	
	var base;
	
	var Nro_fila;
	
	var bt_AdicionarEav,bt_ModificarEav,// usada para crear
	
	bt_EliminarEav,bt_GestionarCampos,
	wn_FormEst,wn_GdCampos;  // los botones
	
	var rc_ = [{
					name: 'nombre'
				},{
					name: 'concepto',type:'bool'
				},{
					name: 'externa'
				},{
					name: 'root',type:'bool'
				},{
					name: 'id',mapping: 'idnomeav'				
				},{
					name: 'fechaini'
				},{
					name: 'fechafin'
				}];
	var stIcono = new Ext.data.Store({
					   	autoload: true,
            		   	url: 'mostrariconos',
                       	reader: new Ext.data.JsonReader({
            						totalProperty: "cant",
            						root: "datos",
            						id: "id"    
            				   },[{
            						name: 'id',mapping: 'id'
            					},{
            						name: 'valor',mapping: 'valor'
            					},{
            						name: 'icono',mapping: 'icono'
            					}]
            			)
			});				
			stIcono.load();
	var chk_boxConcepto = new Ext.form.Checkbox({										
										boxLabel:'Concepto',
										hideLabel:true,
										name:'concepto'
											
			});
	var chk_boxRaiz = new Ext.form.Checkbox({								
								boxLabel:'Ra&iacute;z',
								hideLabel:true,
								name:'root'
									
	});
	
	var chk_boxExterna = new Ext.form.Checkbox({
										boxLabel:'Externa',
										hideLabel:true,
										name:'externa'
											
			});
	var chk_boxInterna= new Ext.form.Checkbox({										
								boxLabel:'Interna',
								hideLabel:true,
								name:'interna'
									
	});	
	
	var fs_chkcol1 = {
            xtype:'fieldset',
            title: 'Marcar si es',
            width:200,
            autoHeight:true,
            items :[chk_boxConcepto,chk_boxRaiz]
            };
		
	var fs_chkcol2 = {
            xtype:'fieldset',
            title: 'Tipo de estructura',
            autoHeight:true,
            width:200,
            items :[chk_boxExterna,chk_boxInterna]
            };
    /*var fechainicio = {
							xtype:'datefield',
							fieldLabel: 'Fecha inicio',
							readOnly:true,
							name: 'fechaini',
							format :'d/m/Y',
							value : new Date(),
							anchor:'95%'						
					  }     
	var fechafin 	= {
						xtype:'datefield',
						readOnly:true,
						fieldLabel: 'Fecha fin',
						format :'d/m/Y',
						name: 'fechafin',
						anchor:'95%'
					}*/
	var fechainicio = new Ext.form.DateField({
						name:'fechainicio',
					        fieldLabel:'Fecha inicio',
                                                readOnly:true,
						value : new Date(),
					        format: 'd/m/Y',
						allowBlank: false,
						anchor:'95%',
						blankText :"Este campo es obligatorio." ,
						readOnly:true
					});				
	var fechafin = new Ext.form.DateField({
						name:'fechafin',
						fieldLabel:'Fecha fin',
                                                readOnly:true,
						format: 'd/m/Y',
						allowBlank: true,
						anchor:'95%',
						blankText :"Este campo es obligatorio.",
						readOnly:true 
					});
	var colf_tabla_1 = {
					columnWidth:.5,
					layout: 'form',
					items: [{
						xtype:'textfield',
						fieldLabel: 'Nombre',
						name: 'nombre',
						anchor:'95%',
						allowBlank:false
						//regex:/^([a-zA-Z]+?[a-zA-Z]*)+[0-9]+$/
					},fechainicio,fs_chkcol1]
		};

	var colf_tabla_2 = {
					columnWidth:.5,
					layout: 'form',
					items: [{
							xtype:'iconcombo',													
							fieldLabel: 'Icono',													
							id: 'idcbicono',
							autoCreate:true,
							store: stIcono,
                            iconClsField: 'icono',													
                            triggerAction: 'all',													
						    forceSelection:true,
							hideLabel:false,						   
						    emptyText:'Seleccione el icono..',
							editable:false,
                            mode: 'local',
                            hiddenName:'id',
						    valueField: 'id',
                            displayField: 'valor',
                            anchor:'95%'	
					},fechafin,fs_chkcol2]
	};
	fechainicio.on('change', function (thiscomp, newvalue, oldvalue){
								fechafin.setMinValue(newvalue);
							});
	fechafin.on('change', function (thiscomp, newvalue, oldvalue){
								fechainicio.setMaxValue(newvalue);
							});			
	var fm_Tablas = new Ext.FormPanel({
									 labelAlign: 'top',
									 frame:true,
									 autoHeight:true,
									 border:'false',
									 items:[{layout:'column',items:[colf_tabla_1,colf_tabla_2]}]
									 
							});
	//--------GRID DE ESTRCUTURAS----------------------------------------------
	bt_AdicionarEav = new Ext.Button({
								text:'Adicionar',
								icon:perfil.dirImg+'adicionar.png',
								id:'btnAdicionarEav',
								iconCls:'btn',
								handler:Onbt_AdicionarEavClick								
						});
	
	bt_ModificarEav = new Ext.Button({	
	        					text:'Modificar',
	        					id:'btnModificarEav',
								icon:perfil.dirImg+'modificar.png',
								disabled:true,
								iconCls:'btn',
								handler:Onbt_ModificarEavClick
								
						})	
	
	bt_EliminarEav = new Ext.Button({
								id:'btnEliminarEav',
								icon:perfil.dirImg+'eliminar.png',
								text:'Eliminar',
								disabled:true,
								iconCls:'btn',
								handler:Onbt_EliminarEav		
						});

	bt_GestionarCampos = new Ext.Button({	
									text:'Gestionar campos',			
									iconCls:'btn',
									//icon:perfil.dirImg+'visualizar.png',
									icon : perfil.dirImg + 'aplicar.png',
									id:'btnGestionarcampos',
									disabled:true,
									handler:Onbt_Gestionarcampos
						});
	var sm_estructura = new Ext.grid.RowSelectionModel({
										singleSelect:true,
										listeners: {rowselect:On_RowClickEst}
									});
		
	st_estructura =  new Ext.data.Store({
									url:rf_estructura,
									listeners:{datachanged:function(){sm_estructura.selectFirstRow();st_relaciones.baseParams = {idtabla :idtabla};
   					st_relaciones.load({params:{start:0,limit:100}});}},
									reader:new Ext.data.JsonReader({
												root:'datos',
												id:'id',
												totalProperty:'cant'
											},rc_)
						});
	st_estructura.l
	col_estructura = [{
							header: 'Nombre', dataIndex: 'nombre',id:'expandir'
					  },{
					  		header: 'Concepto', width:100, dataIndex: 'concepto',renderer: change
					  },{
					  		header: 'Tipo estructura', width:100, dataIndex: 'externa',renderer: changetipo
					  }];

	grid_estructura = new Ext.grid.GridPanel({
							   ddGroup :'secondGridDDGroup',
        					   store   : st_estructura,
        					   region  : 'west',
        					   columns : col_estructura,
							   enableDragDrop : true,
        					   stripeRows     : true,
        					   sm:sm_estructura,        					  
        					   autoExpandColumn : 'expandir',
        					   tbar:[bt_AdicionarEav,bt_ModificarEav,bt_EliminarEav,bt_GestionarCampos],
        					   bbar:new Ext.PagingToolbar({
										pageSize: 100,
										store: st_estructura,
										displayInfo: true,
										displayMsg: 'Resultados de {0} - {1} de {2}',
										emptyMsg: "No hay resultados para mostrar."
									}),
        					   width : 400,	
        					   listeners:{rowdblclick:On_RowdbClick},
        					   title :'Estructuras'
    				});
    st_estructura.load({params:{start:0,limit:100}});
    
		/*** Funciones Privadas*/
    				
	function Onbt_AdicionarEavClick(btn){
			params = null;
	   		url_tablas ="insertar"+rf_estructura;
	   		title = 'Adicionar nivel estructural';
			mostrarWin_FormEst();
		};
		
	function Onbt_ModificarEavClick(btn){			
   			 		url_tablas ="modificar"+ rf_estructura ; 
   			 		params ={idtabla :idtabla}	
   			 		title = 'Modificar nivel estructural';
   					mostrarWin_FormEst();
   					fm_Tablas.getForm().loadRecord(rec);
					Marcar_Check(rec);
                   
	};
	function Marcar_Check(rec){
	               
				    chk_boxExterna.setValue(false);
					chk_boxInterna.setValue(false);
                   
				   if(rec.data.externa==1)
				     chk_boxInterna.setValue(true);
					 else
					 if(rec.data.externa==2)
				     chk_boxExterna.setValue(true);
					 else
					 if(rec.data.externa==3)
					 {
				     chk_boxExterna.setValue(true);
					 chk_boxInterna.setValue(true);
					 }
	};	
	function Onbt_EliminarEav(btn){			
					if(!sm_estructura.getSelected())
						mostrarMensaje(1,'Debe seleccionar el Nivel 1 que desea eliminar.');
					else{
						mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar '+sm_estructura.getSelected().get('nombre')+'?',elimina)
					}
					function elimina(btnPresionado){
							if (btnPresionado == 'ok'){
									Ext.Ajax.request({
											url: 'eliminartabla',
											method:'POST',
											params:{idtabla:idtabla},
											callback: function (options,success,response){
																responseData = Ext.decode(response.responseText);
																mostrarMensaje(responseData.codMsg,responseData.mensaje);
																st_estructura.reload();
																sm_estructura.clearSelections();
																SetEstadoBotonesTbarGridEst();
																//store01.load();
																  
																
											}
									});
							}
			
					}
					
				
		};
	
	function Onbt_Gestionarcampos(btn){
			mostrarWin_GdCampos();
			
	};
	
	function On_RowdbClick( Grid,rowIndex,e ) {
					Nro_fila = rowIndex;
					grid_habilitado = true;
					//grid_relaciones.enable();
					grid_estructura.enableDragDrop.prototype = true;
					//alert(idtabla);
					idtabla = sm_estructura.getSelected().get('id');
   					st_relaciones.baseParams = {idtabla :idtabla};
   					st_relaciones.load({params:{start:0,limit:100}});
   					
   				};
   	function On_RowClickEst(sm, indiceFila, record){
   			SetEstadoBotonesTbarGridEst();
   			idtabla = sm_estructura.getSelected().get('id');
   			rec = record;   		
   			//console.info(record);
   	};
   	function SetEstadoBotonesTbarGridEst(){
			if(sm_estructura.hasSelection()){
					bt_ModificarEav.enable();
					bt_EliminarEav.enable();
					bt_GestionarCampos.enable();
					bt_AdicionarRe.enable();
				}
			else{
					bt_ModificarEav.disable();
					bt_EliminarEav.disable();
					bt_GestionarCampos.disable();
					bt_AdicionarRe.disable();
				}
	};
   	function change(val){
        if(val == 1){
            return  'Si';     
        }else if(val == 0){
             return  'No';
        }
        return val;
    }	
    
    function changetipo(val){
        if(val == 1){
            return  'Plantilla';     
        }else if(val == 2){
             return  'Estructura';
        }else if(val == 3){
             return  'Estructura/Plantilla';
        }
        return val;
    }	
	
	function agregarfila(record, index, allItems) {
				var foundItem = st_estructura.find('nombre', record.data.nombre);
				if (foundItem  == -1) {
					st_estructura.add(record);
					InsertaEav(record);
					st_estructura.sort('nombre', 'ASC');
					//ddSource.grid.store.remove(record);
				}
			}
	
	function InsertaEav(recor){
				params ={idtabla:idtabla,idrelacion:recor.data.id};
					Ext.Ajax.request({
							url: 'insertar'+rf_relaciones,
							method:'POST',
							params:params,
							callback: function (options,success,response){
												responseData = Ext.decode(response.responseText);
												mostrarMensaje(responseData.codMsg,responseData.mensaje);
												st_relaciones.reload();
												sm_estructura.clearSelections();
												sm_estructura.selectRow(Nro_fila);
												//SetEstadoBotonesTbarGrid();
							}
					});				
				}
	
	function mostrarWin_FormEst(){
			if(!wn_FormEst){
					wn_FormEst = new Ext.Window({
											title:'Confeccionar estructura',
											layout:'fit',
											width:500,
											autoHeight:true,
											bodyStyle:'padding:5px;',
											items:fm_Tablas,
											closeAction:'hide',
											listeners:{hide:onWnFormEAVhide},
											modal:true,
											buttons:[{
												text:'Cancelar',
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												handler:OnCancelarClick 
											},{
												text:'Aceptar',
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												handler:OnAceptarClick
											}]
								});
			}	
		wn_FormEst.setTitle(title);
		wn_FormEst.show(this);
	};
	var gridCampos = new Erp.ec.GridGestionarCampos();
	
	function mostrarWin_GdCampos(){
				var base = rec.data.id; 
				gridCampos.getStore().baseParams = {idtabla:base};
				gridCampos.getStore().load({params:{start:0,limit:100}})
				gridCampos.SetidTabla(base);
				
			if(!wn_GdCampos){				
					wn_GdCampos = new Ext.Window({
											title:'Gestionar campos',
											layout:'fit',
											width:500,
											autoHeight:true,
											bodyStyle:'padding:5px;',
											items:gridCampos,
											closeAction:'hide',
											modal:true//,											listeners:{beforeshow }
								});
			}								
		wn_GdCampos.show(this);
	};
	function onWnFormEAVhide(){
			fm_Tablas.getForm().reset();
		};
	function OnCancelarClick(btn){
				fm_Tablas.getForm().reset();
				wn_FormEst.hide();
	};
	
	
	function OnAceptarClick(btn){
	    				sbmt_EnviarfmTablas();
	};
	function Validar_Seleccion(){
	         if(!chk_boxExterna.getValue() && !chk_boxInterna.getValue())
					    {	
						Ext.Msg.show({  title:'Mensaje de Error',
												msg: 'Debe seleccionar un tipo de estructura.',
												buttons: ( Ext.Msg.OK),                   
												animEl: document.body,                   
												icon: Ext.MessageBox.ERROR})
												return true;
						}
						
						
	   return false;
	  }
	function sbmt_EnviarfmTablas(){
					
					if(Validar_Seleccion())
					  return;
						
					if (fm_Tablas.getForm().isValid())
						{
							fm_Tablas.getForm().submit(
								{
									url:url_tablas,
									params:params,	
									failure: function(form, action){
											mostrarMensaje(action.result.codMsg,action.result.mensaje);
											if(action.result.codMsg != 3)
												{   
													st_estructura.reload();
														fm_Tablas.getForm().reset();
															wn_FormEst.hide();
											    }
									}
								,waitMsg:'Enviando los datos...'})
							
						}												
						else
							{	 Ext.Msg.show({  title:'Error',
												msg: 'Por favor verifique nuevamente que hay campo(s) con valor(es) incorrecto(s).',
												buttons: ( Ext.Msg.OK),                   
												animEl: document.body,                   
												icon: Ext.MessageBox.ERROR}) 
								//mostrarMensaje(3,'Por favor verifique nuevamente que hay campo(s) con valor(es) incorrecto(s).');					
						}
						
					
						
				};
	//--------------Segundo Grid---------------------------------------------------------
	bt_AdicionarRe = new Ext.Button({
								text:'Adicionar',
								icon:perfil.dirImg+'adicionar.png',
								id:'btnAdicionarEav',
								iconCls:'btn',
								disabled:true,
								handler:Onbt_AdicionarRe							
						});
	
	bt_EliminarRe = new Ext.Button({
								id:'btnEliminarEav',
								icon:perfil.dirImg+'eliminar.png',
								text:'Eliminar',
								disabled:true,
								iconCls:'btn',
								handler:Onbt_EliminarRe		
						});
	col_relaciones = [{
							header: 'Nombre', dataIndex: 'nombre',id:'expandir'
					  }];
	
	var sm_relaciones = new Ext.grid.RowSelectionModel({
										singleSelect:true,
										listeners: {rowselect:On_RowClickRe}
									});
	st_relaciones =  new Ext.data.Store({
									url:"buscar"+rf_relaciones,
									listeners:{datachanged:function(){marca()}},
									reader:new Ext.data.JsonReader({
												root:'datos',
												id:'idnomeav',
												totalProperty:'cant'
											},rc_)
						});
	 grid_relaciones = new Ext.grid.GridPanel({
							ddGroup          : 'firstGridDDGroup',
						    store            : st_relaciones,
						    columns          : col_relaciones,
							enableDragDrop   : true,
						    stripeRows       : true,
						    autoExpandColumn : 'expandir',
						    width            : 200,
						    //disabled:true,
						    sm:sm_relaciones,
							region           : 'center',
						    title            : 'Relaciones',
						    tbar:[bt_AdicionarRe,bt_EliminarRe],
        					bbar:new Ext.PagingToolbar({
										pageSize: 100,
										disabled:true,
										store: st_relaciones,
										displayInfo: true,
										displayMsg: 'Resultados de {0} - {1} de {2}',
										emptyMsg: "No hay resultados para mostrar."
									})
						});
	
	
	function Onbt_AdicionarRe(){
			recor = rec;
			agregarfilare(recor)
	};
	function On_RowClickRe(sm, indiceFila, record){
   					recRe = record;
   					SetEstadoBotonesTbarGridRe();
   				};
   				
   	function SetEstadoBotonesTbarGridRe(){
			if(sm_relaciones.hasSelection()){
					bt_EliminarRe.enable();
				}
			else{
					bt_EliminarRe.disable();
				}
	};
	/*Funcion para que marque el primer elemento del grid en caso que no este vacio*/
	function marca() {
		if (st_relaciones.getCount()!=0)
			{
				sm_relaciones.selectFirstRow();
			}	
		else
			{
				bt_EliminarRe.disable();				
			}
	}

	function Onbt_EliminarRe(){						
					if(!sm_relaciones.getSelected())
						mostrarMensaje(1,'Debe seleccionar el que desea eliminar.');
					else{
						mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar '+sm_relaciones.getSelected().get('nombre')+' ?',elimina)
					}
					function elimina(btnPresionado){
							if (btnPresionado == 'ok'){
									Ext.Ajax.request({
											url: 'eliminar'+ rf_relaciones,
											method:'POST',
											params:{idtabla:idtabla,idrelacion:recRe.data.id},
											callback: function (options,success,response){
																responseData = Ext.decode(response.responseText);
																mostrarMensaje(responseData.codMsg,responseData.mensaje);
																sm_relaciones.clearSelections();
																SetEstadoBotonesTbarGridRe();
																st_relaciones.reload();
											}
									});
							}
			
					}
					
				
		
	};
	
	//st_relaciones.load({params:{start:0,limit:100}});
//----------------------ViewPort ------------------------------------------------------------
	var vp_GestionEav = new Ext.Viewport({
									layout:'border',
									items:[grid_estructura,grid_relaciones]
							});	
//------------------------Eventos para Arrastrar y Lanzar---------------------------------------
	// This will make sure we only drop to the view container
	var firstGridDropTargetEl =  grid_estructura.getView().el.dom.childNodes[0].childNodes[1];
	var firstGridDropTarget = new Ext.dd.DropTarget(firstGridDropTargetEl, {
		ddGroup    : 'firstGridDDGroup',
		copy       : true,
		notifyDrop : function(ddSource, e, data){	
			//console.info(ddSource.dragData.selections);
			Ext.each(ddSource.dragData.selections ,agregarfila);
			return(true);
		}
	}); 	

	var secondGridDropTargetEl = grid_relaciones.getView().el.dom.childNodes[0].childNodes[1]
	
	var destGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {
		ddGroup    : 'secondGridDDGroup',
		copy       : false,
		notifyDrop : function(ddSource, e, data){			
			Ext.each(ddSource.dragData.selections ,agregarfilare);
			return(true);
		}
	}); 
	function agregarfilare(record, index, allItems) {				
				var foundItem = st_relaciones.find('nombre', record.data.nombre);				
				if(grid_habilitado){
						if (foundItem  == -1) {
							st_relaciones.add(record);	
							sm_estructura.clearSelections();
							sm_estructura.selectRow(Nro_fila);
							InsertaEav(record);
							st_relaciones.sort('nombre', 'ASC');
						}
					else{mostrarMensaje(3,'Ya existe esa relaci&oacute;n.');}
				}
			};
	
	
};
function CargarInterfaz(){
	Ext.QuickTips.init();
	//Ext.form.Field.prototype.msgTarget = 'side';
	Ext.onReady(function(){	var eav = new gestionarEav();})
};

CargarInterfaz();
