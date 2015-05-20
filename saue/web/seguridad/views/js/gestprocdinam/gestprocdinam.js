var perfil = window.parent.UCID.portal.perfil;
		UCID.portal.cargarEtiquetas('gestprocdinam', function(){cargarInterfaz();});
		////------------ Inicializo el singlenton QuickTips ------------////
		Ext.QuickTips.init();
		////------------ Declarar Variables ------------////
		var winIns, winMod;
		letrasnumeros = /(^([a-zA-ZáéíóúñÑ])+([a-zA-ZáéíóúñÑ\d\.\_\s]*))$/; 
		function cargarInterfaz(){
		////------------ Botones ------------////
		btnAdicionar = new Ext.Button({id:'btnAgrPro', icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:'Adicionar'/*perfil.etiquetas.lbBtnAdicionar*/, handler:function(){winForm('Ins');} });
		btnModificar = new Ext.Button({id:'btnModPro', disabled:true,  icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:'Modificar'/*perfil.etiquetas.lbBtnModificar*/, handler:function(){winForm('Mod');} });
		btnEliminar = new Ext.Button({id:'btnEliPro', disabled:true,    icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:'Eliminar'/*perfil.etiquetas.lbBtnEliminar*/, handler:function(){eliminarProceso();}});	
        UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
		////------------- Store del Grid de procesos -------------- ////
		var stGpProceso =  new Ext.data.Store({
		url: 'listarprocesos',
			reader:new Ext.data.JsonReader({
				totalProperty: "cantidad_filas",
				root: "datos",
				id: "idproceso"
				},
				[
					{name:'idproceso',mapping:'idproceso'},
					{name:'descripcion',mapping:'descripcion'},
					{name:'denominacion',mapping:'denominacion'},
				])
		});
		
		////------------ Establesco modo de seleccion de grid (single) ---------////
		sm = new Ext.grid.RowSelectionModel({singleSelect:false});
		sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record){
						btnModificar.enable();
						btnEliminar.enable();
					}, this);
		
		var gpProcesos = new Ext.grid.GridPanel({
			frame:true,
			region:'center',
			iconCls:'icon-grid',
			autoExpandColumn:'expandir',
			store:stGpProceso,
			sm:sm, 
			columns: [
						{hidden: true, hideable: false, dataIndex: 'idproceso'},
						{header:'Denominaci&oacute;n'/*perfil.etiquetas.lbTitMsgDenominacion*/,width:200,dataIndex: 'denominacion'},
						{id:'expandir',header:'Descripci&oacute;n'/*perfil.etiquetas.lbTitMsgDescripcion*/, width:200, dataIndex: 'descripcion'}
		 			 ],
			loadMask:{store:stGpProceso}, 
            tbar:
                [
                    new Ext.Toolbar.TextItem({text:'Denominaci&oacute;n'}),
                    denrol = new Ext.form.TextField({width:80, id: 'denrol'}),
                    new Ext.menu.Separator(),
                    new Ext.Button({icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:'Buscar', handler:function(){buscarnombrerol(denrol.getValue())}})
					
			    ],
			bbar:new Ext.PagingToolbar({
			pageSize: 15,
			id:'ptbaux',
			store: stGpProceso,
			displayInfo: true,
			displayMsg:''/*perfil.etiquetas.lbTitMsgResultados*/,
			emptyMsg:''/*perfil.etiquetas.lbTitMsgNingunresultadoparamostrar*/
		})
		});
		
		var pGenProcesos = new Ext.Panel({
			layout:'border',
			title:'Gestionar Procesos',
			items:[gpProcesos],
			tbar:[btnAdicionar,btnModificar,btnEliminar]
		});
		stGpProceso.load({params:{start:0,limit:15}});
		
		////---------- Viewport ----------////
		var vpGestRol = new Ext.Viewport({
			layout:'fit',
			items:pGenProcesos
		})
        ////---------- Formulario de campos ----------////
		var fpProcesos = new Ext.FormPanel({
			labelAlign: 'top',
		    frame:true,
			items:[{
						xtype:'textfield',
						allowBlank:false,
						fieldLabel:'Denominaci&oacute;n',
						anchor:'50%',
						id:'denominacion'	
						},{
						xtype:'textarea',
						allowBlank:false,
						fieldLabel:'Descripci&oacute;n',
						anchor:'100%',
						id:'descripcion'	
					}]
		});
		
		
		////---------- Cargar ventanas ----------////
		function winForm(opcion){
		switch(opcion){
			case 'Ins':{
				if(!winIns)
				{
					winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
												title:'Adicionar proceso',width:350,height:220,
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
																	handler:function(){adicionarProceso('apl');}
																},
																
																{	
																	icon:perfil.dirImg+'aceptar.png',
																	iconCls:'btn',
																	text:perfil.etiquetas.lbBtnAceptar,
																	handler:function(){adicionarProceso();}
																}
														]
											});
					
					
				}
				fpProcesos.getForm().reset(); 
				winIns.add(fpProcesos);
				winIns.doLayout();
				winIns.show();
				
			}break;
			case 'Mod':{
						if(!winMod)
						{
								winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
									           title:'Modificar proceso',width:350,height:220,
									buttons:[
												{
													icon:perfil.dirImg+'cancelar.png',
													iconCls:'btn',
													text:perfil.etiquetas.lbBtnCancelar,
													handler:function(){	winMod.hide();}
												},
												
												{	
													icon:perfil.dirImg+'aceptar.png',
													iconCls:'btn',
													text:perfil.etiquetas.lbBtnAceptar,
													handler:function(){modificarProceso();}
												}
											]
										});
									
						}
						fpProcesos.getForm().reset(); 
						winMod.add(fpProcesos);
						winMod.doLayout();
						winMod.show();	
						
						
						
                       /* winMod.doLayout();																	
						winMod.show();*/    
						fpProcesos.getForm().loadRecord(sm.getSelected());	
				}break;
			}
		 }
		
		////------------ Adicionar Procesos ------------////
		function adicionarProceso(apl){
			if (fpProcesos.getForm().isValid()){
				fpProcesos.getForm().submit({
					url:'insertarproceso',
					waitMsg:perfil.etiquetas.lbMsgEsperaInsAcc,
					failure: function(form, action){
						if(action.result.codMsg != 3){
							mostrarMensaje('1',perfil.etiquetas.lbMsgAddProc); 
							fpProcesos.getForm().reset(); 
							if(!apl) 
								winIns.hide();
							stGpProceso.reload();
							sm.clearSelections();
							btnModificar.disable();
							btnEliminar.disable();
						}
						if(action.result.codMsg == 3){
						//mostrarMensaje(action.result.codMsg,action.result.mensaje);
						mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);
						}
						
					}
				});
			}
	        else
	            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);        
		}
		////------------ Modififcar Proceso ------------////
		function modificarProceso(){
			if (fpProcesos.getForm().isValid())
			{
				fpProcesos.getForm().submit({
					url:'modificarproceso',
					waitMsg:perfil.etiquetas.lbMsgEsperaModAcc, 
					params:{idproceso:sm.getSelected().data.idproceso},
					failure: function(form, action){
						if(action.result.codMsg != 3)
						{
							mostrarMensaje('1',perfil.etiquetas.lbMsgModProc); 
							stGpProceso.reload();
							winMod.hide();
						}
						if(action.result.codMsg == 3) 
							mostrarMensaje('3',perfil.etiquetas.lbMsgModErr);
					}
				});
			}
	        else
	            mostrarMensaje('3',perfil.etiquetas.lbMsgErrorEnCamops);        
		}
		////------------ Eliminar Proceso ------------////
		function eliminarProceso(){
			var arrProcesosElim = sm.getSelections();
        	var arrayProcElim = [];
        	for (var i=0;i<arrProcesosElim.length;i++)
            {
				arrayProcElim.push(arrProcesosElim[i].data.idproceso);
			}
			mostrarMensaje(2,perfil.etiquetas.lbMsgEliminar,elimina);
			function elimina(btnPresionado){
				if (btnPresionado == 'ok')
				{
					Ext.Ajax.request({
						url: 'eliminarproceso',
						method:'POST',
						params:{arrayProcElim:Ext.encode(arrayProcElim)},
						callback: function (options,success,response){
								responseData = Ext.decode(response.responseText);
								if(responseData.codMsg == 1)
								{
									mostrarMensaje('1',perfil.etiquetas.lbMsgDelProc);
									stGpProceso.reload();
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
        ////------------ Buscar Proceso ------------////
       	function buscarnombrerol(denproceso)
        {
        	stGpProceso.load({params:{denproceso:denproceso,start:0,limit:15}});
        } 
		
		}