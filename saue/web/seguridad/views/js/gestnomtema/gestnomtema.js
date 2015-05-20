		var perfil = window.parent.UCID.portal.perfil;
		perfil.etiquetas = Object();
		UCID.portal.cargarEtiquetas('gestnomtema', cargarInterfaz);
		
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
				
		////------------ Declarar Variables ------------////
		var winIns, winMod,regTema,editorTema,editorAut;
		var auxIns = false;
		var auxMod = false;
		var auxDel = false;
		var auxMod1 = false;
		var auxDel1 = false;
		var auxIns2 = true;
		var auxMod2 = true;
		var auxDel2 = true;
		var denMod,abrevMod,desMod;
		var stGpTema;
		
		////------------ Area de Validaciones ------------////
		var tipos, abreviatura;
		tipos = /(^([a-zA-ZáéíóúñüÑ])+([a-zA-ZáéíóúñüÑ\d\.\-\@\#\_ ]*))$/;
		abreviatura = /^([a-zA-ZáéíóúñüÑ]+[a-zA-ZáéíóúñüÑ\d\.\-\@\#\_]*)+$/;
		
		function cargarInterfaz()
		{
			////------------ Botones ------------////
			btnAdicionar = new Ext.Button({id:'btnAgrTema', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}  });
			btnModificar = new Ext.Button({disabled:true,id:'btnModTema', hidden:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnModificar, handler:function(){winForm('Mod');} });
			btnEliminar = new Ext.Button({disabled:true,id:'btnEliTema', hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar,handler:function(){eliminarTema();}  });
			btnAutenticacion= new Ext.Button({id:'btnAuten', hidden:false, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEditarAut ,handler:function(){
				Espera=Ext.Msg.show({
								progress:true,
								title:perfil.etiquetas.lbLoadEditor,
								closable:false,
								modal:true,
								msg:perfil.etiquetas.lbLoadTextEditorAut,
								progressText:'0%',
								width:350
				}) ; 
				
				if(editorAut){ 
					  editorAut.cargarConfig();
				 }else{ 
					  editorAut=new Ext.Autenticacion(); 
					  editorAut.cargarConfig();
				 }
				
				
				}});
			
			
			
			btnAyuda = new Ext.Button({id:'btnAyuTema', hidden:false, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda ,handler:function(){
			
			
				
				 Ext.Ajax.request({
			url: 'copiando',
			method:'POST',
			
			params:{nombre_tema:sm.getLastSelected().data.idtema,bandera:true},
                     
			callback: function (options,success,response){
									responseData = Ext.decode(response.responseText);
									
									//if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
							}
		});
				
				//der('El tema se inserto correctamente, para informacion adicional contacte con el Admin.');
				
				}});
				
			
				
			
			UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
			
			////------------ Store del Grid de Temas ------------//// 
			stGpTema =  new Ext.data.Store({
					
					fields:[
						 	{name:'idtema'},
						 	{name:'denominacion'},
						 	{name:'descripcion'},
						 	{name:'abreviatura'},
						 	{name:'dinamico'},
						],
					
						
						 proxy: {
         type: 'ajax',
        url: 'cargarnomtema',
        actionMethods:{ //Esta Linea es necesaria para el metodo de llamada POST o GET
                                        
            read:'POST'
        },
        reader:{
            totalProperty: "cantidad_filas",
            root:"datos",
            id: "idtema"
        }
    }
			});
			
			////------------ Establesco modo de seleccion de grid (single) ------------////
			sm = Ext.create('Ext.selection.RowModel',{mode:'SINGLE', allowDeselect:true});
			sm.on('beforeselect', function (smodel, rowIndex, keepExisting, record){
									btnModificar.enable();
									btnEliminar.enable();
								}, this);
			////------------ Defino el grid de Temas ------------////
			var gpTema= new Ext.grid.GridPanel({
				frame:true,
				header:false,
				region:'center',
				iconCls:'icon-grid',
                                paginate:true,
				autoExpandColumn:'expandir',
				store:stGpTema,
				selModel:sm,
				columns: [
							{hidden: true, hideable: false,  dataIndex: 'idtema'},
							{ id:'expandir',header: perfil.etiquetas.lbTitDenominacion,width:200,  dataIndex: 'denominacion'},
							{ header: perfil.etiquetas.lbTitAbreviatura,width:200, dataIndex: 'abreviatura'},	
							{ header: perfil.etiquetas.lbTitDescripcion,flex:1, width:200, dataIndex: 'descripcion'},
							{ header: perfil.etiquetas.lbTitTipoTema, width:200, dataIndex: 'dinamico',renderer:function change(val){
        if(val==false){
            return '<img src="../../views/images/quitar.png"/><span style="color:red;font:bold;margin-left:7px;">'+perfil.etiquetas.lbGridNoDinamico+'</span>';
        }else {
            return '<img src="../../views/images/tema.png"/><span style="color:green;font:bold;;margin-left:7px;">'+perfil.etiquetas.lbGridDinamico+'</span>';
        }
        return val;
    }}
		
				 		 ],
				loadMask:{store:stGpTema},			
				bbar:new Ext.PagingToolbar({
				pageSize: 15,
				id:'ptbaux',
				store: stGpTema,
				displayInfo: true,
				displayMsg: perfil.etiquetas.lbMsgbbarI,
				emptyMsg: perfil.etiquetas.lbMsgbbarII
			})
			});
			////------------ Trabajo con el PagingToolbar ------------////
			Ext.getCmp('ptbaux').on('change',function(){
				sm.select(0);
			},this)
			
			////------------ Renderiar el arbol ------------////
			var panel = new Ext.Panel({
				layout:'border',
				title:perfil.etiquetas.lbTitPanelTit,
				renderTo:'panel',
				items:[gpTema],
				tbar:[btnAdicionar,btnModificar,btnEliminar,'->',btnAutenticacion],
				keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    				if(auxDel && auxDel1 && auxDel2)
		    					eliminarTema();
		    			}
		    		  },
		    		  {
		    		  	key:"i",
		    			alt:true,
		    			fn: function(){
							if(auxIns && auxIns2)		    			
		    					winForm('Ins');}
		    		  },
		    		  {
		    		  	key:"m",
		    			alt:true,
		    			fn: function(){
		    				if(auxMod && auxMod1 && auxMod2)
		    					winForm('Mod');}
		    		  }])
			});
			stGpTema.on('load',function(){
				if(stGpTema.getCount() != 0)
				{
					auxMod1 = true;
					auxDel1 = true;
				}
				else
				{
					auxMod1 = false;
					auxDel1 = false;
				}
			},this)
			////------------ Eventos para hotkeys ------------////
			btnAdicionar.on('show',function(){
				auxIns = true;
			},this)
			btnEliminar.on('show',function(){
				auxDel = true;
			},this)
			btnModificar.on('show',function(){
				auxMod = true;
			},this)
			
			////------------ ViewPort ------------////
			var vpTema = new Ext.Viewport({
				layout:'fit',
				items:panel
			})
			stGpTema.load({params:{start:0, limit:10}});
			
			////------------ Formulario ------------////
			regTema = new Ext.FormPanel({
					labelAlign: 'top',
					frame:true,
					bodyStyle:'padding:5px 5px 0',
					items: [{
							layout:'column',
							items:[{
										columnWidth:.5,
										layout:'form',
										margin:'5 5 5 5',
                                        border:0,
                                       
										items:[{
													xtype:'textfield',
													fieldLabel:perfil.etiquetas.lbFLDenominacion,
													id:'denominacion',
													allowBlank:false,
                                                    maxLength:40,    
							            			blankText:perfil.etiquetas.lbMsgBlank,
							            			regex:tipos,
													regexText:perfil.etiquetas.lbMsgregexI,
													anchor:'95%',
													 labelAlign: 'top',
													 name:'denominacion'
											  }]
								   },
								   {
										columnWidth:.5,
										layout:'form',
										margin:'5 5 5 5',
                                                                border:0,
										items:[{
													xtype:'textfield',
													fieldLabel:perfil.etiquetas.lbFLAbreviatura,
													id:'abreviatura',
                                                    maxLength:40,    
													allowBlank:false,
						            				blankText:perfil.etiquetas.lbMsgBlank,
						            				regex:abreviatura,
													regexText:perfil.etiquetas.lbMsgregexI,
													 labelAlign: 'top',
													anchor:'95%',
													name:'abreviatura'
											   }]
								   },
								   {
										columnWidth:1,
										layout:'form',
										margin:'5 5 5 5',
                                                                border:0,
										items:[{
													xtype:'textarea',
													fieldLabel:perfil.etiquetas.lbFLDescripcion,
													id:'descripcion',
													 labelAlign: 'top',
													anchor:'100%',
													name:'descripcion'
											  }]
								   },{
										columnWidth:1,
										layout:'form',
										margin:'5 5 5 5',
                                                                border:0,
										items:[{
													xtype:'checkbox',
													fieldLabel:perfil.etiquetas.lbGridDinamico,
													id:'dinamico',
													 labelAlign: 'top',
													anchor:'100%',
													name:'dinamico'
											  }]
								   }]
						}]
			});	
			
			////------------ Cargar la ventana ------------////
			function winForm(opcion){
				switch(opcion){
					case 'Ins':{
						if(!winIns)
						{
							winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
								resizable: false,
								title:perfil.etiquetas.lbTitVentanaTitI,width:350,autoHeight:true,
								buttons:[
								{
									icon:perfil.dirImg+'cancelar.png',
									iconCls:'btn',
									text:perfil.etiquetas.lbBtnCancelar,
									handler:function(){winIns.hide();}
								},
								{	
									icon:perfil.dirImg+'aplicar.png',
									iconCls:'btn',
									text:perfil.etiquetas.lbBtnAplicar,
									handler:function(){adicionarTema('apl');}
								},
								{
									icon:perfil.dirImg+'aceptar.png',
									iconCls:'btn',
									text:perfil.etiquetas.lbBtnAceptar,
									handler:function(){adicionarTema();}
								}]
							});
							winIns.on('show',function(){
								auxIns2 = false;
								auxMod2 = false;
								auxDel2 = false;
							},this)
							winIns.on('hide',function(){
								auxIns2 = true;
								auxMod2 = true;
								auxDel2 = true;
							},this)
						}
						regTema.getForm().reset(); 
						Ext.getCmp('dinamico').show();
						winIns.add(regTema);
						winIns.doLayout();
						winIns.show();
						}break;
						case 'Mod':{
							if(sm.getLastSelected().data.dinamico==false){
							if(!winMod)
							{
								winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
									resizable: false,
									title:perfil.etiquetas.lbTitVentanaTitII,width:350,autoHeight:true,
									buttons:[
									{
										icon:perfil.dirImg+'cancelar.png',
										iconCls:'btn',
										text:perfil.etiquetas.lbBtnCancelar,
										handler:function(){winMod.hide();}
									},
									{	
										icon:perfil.dirImg+'aceptar.png',
										iconCls:'btn',
										text:perfil.etiquetas.lbBtnAceptar,
										handler:function(){modificarTema();}
									}]
								});
								winMod.on('show',function(){
									auxIns2 = false;
									auxMod2 = false;
									auxDel2 = false;
								},this)
								winMod.on('hide',function(){
									auxIns2 = true;
									auxMod2 = true;
									auxDel2 = true;
								},this)
							}
							winMod.add(regTema);
							Ext.getCmp('dinamico').hide();				
							winMod.doLayout();
							winMod.show();
							regTema.getForm().loadRecord(sm.getLastSelected());
                                                        denMod = Ext.getCmp('denominacion').getValue();
                                                        abrevMod = Ext.getCmp('abreviatura').getValue();
                                                        desMod = Ext.getCmp('descripcion').getValue();
						 }else{
							Espera=Ext.Msg.show({
								progress:true,
								title:perfil.etiquetas.lbLoadEditor,
								closable:false,
								modal:true,
								msg:perfil.etiquetas.lbLoadTextEditor,
								progressText:'0%',
								width:350
								}) ; 
							if(editorTema){
							   //alert('created')
							  editorTema.cargarConfig();
						  }else{
							  
							  editorTema=new Ext.Editor();
							  editorTema.cargarConfig();
							  }
						  }
							 
					}break;
				}
			}
			
			////------------ Adicionar Tema ------------////
			function adicionarTema(apl){
				if (regTema .getForm().isValid())
				{
					regTema .getForm().submit({
						url:'insertartema',
						waitMsg:perfil.etiquetas.lbMsgFunAdicionarMsg,
						failure: function(form, action){
							if(action.result.codMsg != 3)
							{
								//if(action.result.codMsg==1)
									
								//	mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								regTema .getForm().reset(); 
								stGpTema.load();					
								if(!apl) 
								winIns.hide();
								sm.clearSelections();
								btnModificar.disable();
								btnEliminar.disable();
							}
							//if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						}
					});
				}
                else
                    mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
			}
			
			////------------ Modififcar Tema ------------////
			function modificarTema(){
				if (regTema .getForm().isValid())
				{
                                        var dMod = denMod != Ext.getCmp('denominacion').getValue();
                                        var aMod = abrevMod != Ext.getCmp('abreviatura').getValue();
                                        var deMod = desMod != Ext.getCmp('descripcion').getValue(); 
                                        if(dMod||aMod||deMod){
					regTema .getForm().submit({
						url:'modificartema',
						waitMsg:perfil.etiquetas.lbMsgFunModificarMsg,
						params:{idtema:sm.getLastSelected().data.idtema},
						failure: function(form, action){
							if(action.result.codMsg != 3)
							{
								//if(action.result.codMsg==1)
								//	mostrarMensaje(action.result.codMsg,action.result.mensaje); 
								stGpTema.load();
								winMod.hide();
							}
							if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
						}
					});
                                }
                                else
                                  mostrarMensaje(3,perfil.etiquetas.NoModify);
				}
                else
                    mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);                
			}
			
			////------------ Eliminar  Tema ------------////
			function eliminarTema(){
				mostrarMensaje(2,perfil.etiquetas.lbMsgFunEliminarMsgI,elimina);
				function elimina(btnPresionado){
					if (btnPresionado == 'ok')
					{
						Ext.Ajax.request({
							url: 'eliminarnomtema',
							method:'POST',
							params:{idtema:sm.getLastSelected().data.idtema,din:sm.getLastSelected().data.dinamico,nombre_tema:sm.getLastSelected().data.abreviatura},
							callback: function (options,success,response){
									responseData = Ext.decode(response.responseText);
									if(responseData.codMsg == 1)
									{
										//if(responseData.codMsg==1)
										//	mostrarMensaje(responseData.codMsg,responseData.mensaje); 
										stGpTema.load();
										sm.clearSelections();
										btnModificar.disable();
										btnEliminar.disable();
									}
									if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
							}
						});
					}
				}
			}
		}
		
