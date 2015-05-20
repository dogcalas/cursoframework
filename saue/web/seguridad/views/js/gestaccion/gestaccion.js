	var perfil = window.parent.UCID.portal.perfil;
	perfil.etiquetas = Object();
	UCID.portal.cargarEtiquetas('gestaccion',cargarInterfaz);
	
	////------------ Inicializo el singlenton QuickTips ------------////
	Ext.QuickTips.init();
		
	////------------ Declarar Variables ------------////
	var winIns, winMod, idfuncionalidad,winSelecaccion;
	var auxIns = false;
	var auxMod = false;
	var auxDel = false;
	var auxIns2 = false;
	var auxMod2 = false;
	var auxDel2 = false;
	var auxIns3 = true;
	var auxMod3 = true;
	var auxDel3 = true;
	var auxBus3 = true;
	var auxBus = false;
	var auxDelete = true;
        var auxDelShowW=true;
        var criterioSel;
        var servidorSeleccionado="",gestorSeleccionado="",bdSeleccionada="",esquemaSeleccionado="",objetoSeleccionado="",subsistemaSeleccionado="",servicioSeleccionado="";
	var denMod,abrevMod,desMod;
	////------------ Area de Validaciones ------------////
	var tipos;
	tipos =  /(^([\/a-zA-ZáéíóúñüÑ])+([\/a-zA-ZáéíóúñüÑ\d\_\s]*))$/;
	function cargarInterfaz(){
	
        var LastValueEntidad="";
        var LastValueEsquema="";
        var LastValueBD="";
        var LastValueGestor="";
        var LastValueServidor="";
        var LastValueSubsistema="";
         var LastValueServicio="";
        var FilasColumnasModificadas=new Array();
        ////------------ Botones ------------////
	btnAdicionar = new Ext.Button({disabled:true,id:'btnAgrAccion', hidden:true, icon:perfil.dirImg+'adicionar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAdicionar, handler:function(){winForm('Ins');}});
	btnModificar = new Ext.Button({disabled:true,id:'btnModAccion', hidden:true, icon:perfil.dirImg+'modificar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnModificar,handler:function(){winForm('Mod');} });
	btnEliminar = new Ext.Button({disabled:true,id:'btnEliAccion', hidden:true, icon:perfil.dirImg+'eliminar.png', iconCls:'btn', text:perfil.etiquetas.lbBtnEliminar, handler:function(){eliminarAccion();}  });
	/*btnAyuda = new Ext.Button({id:'btnAyuAccion', hidden:true, icon:perfil.dirImg+'ayuda.png', iconCls:'btn', text:perfil.etiquetas.lbBtnAyuda });*/
        btnBuscar = new Ext.Button({disabled:true,icon:perfil.dirImg+'buscar.png',iconCls:'btn',text:perfil.etiquetas.lbBtnBuscar, handler:function(){if(accion.isValid())buscaraccion(accion.getValue());}}) 
        btnExplorar = new Ext.Button({icon:perfil.dirImg+'buscar.png',iconCls:'btn', style:'margin-top:18px', text:'', handler:function(){buscaraccionescontrolador();}});  
        btnRecargar = new Ext.Button({disabled:false,icon:perfil.dirImg+'actualizar.png',iconCls:'btn',text:perfil.etiquetas.lbBtnRecargar,handler:function(){arbolAcc.getRootNode().reload();}});  
    UCID.portal.cargarAcciones(window.parent.idFuncionalidad);
	
        /*
         *Codigo agregado por Jose Eduardo
         */
        
        ///----Store del combocriterios----///
       var stcombo =   new Ext.data.Store({
           url: 'getcriterioSeleccion',
           autoLoad:true,
           reader:new Ext.data.JsonReader({
           id:"criterio"
           },[{
            name: 'criterio',
            mapping:'criterio'
           }
           ])
        });
    ///----Va a traer los criterios de seleccion (Tablas, Secuencias,Vistas,Funciones)----///
    var combocriterios = new Ext.form.ComboBox({
           fieldLabel:'Buscar',
           xtype:'combo',
           store:stcombo,
           disabled:true,
           valueField:'criterio',
           displayField:'criterio',
           triggerAction: 'all',
           editable: false,
           mode: 'local',
           emptyText:perfil.etiquetas.lbCbCriterioEmptyText,
           anchor:'50%',
           width:100
     });
    
    combocriterios.on('select',function(){levantarVentanaObjetosBD();})
        
        ////------------ Arbol Acciones ------------////
	arbolAcc = new Ext.tree.TreePanel({
		title:perfil.etiquetas.lbTitArbolSistemas,
		collapsible:true,
		autoScroll:true,
		region:'west',
		split:true,
		width:'37%',
		bbar:['->',btnRecargar],
		loader: new Ext.tree.TreeLoader({
			dataUrl:'cargarsistfunc',
			listeners:{'beforeload':function(atreeloader, anode){ 
							atreeloader.baseParams = {};
							if(anode.attributes.idsistema)			
								atreeloader.baseParams.idsistema = anode.attributes.idsistema												
						}
					}
				
		})
	});
	////------------ Crear nodo padre del arbol ------------////
	padreArbolAcc = new Ext.tree.AsyncTreeNode({
	      text:perfil.etiquetas.lbRootNodeArbolSubsist,
		  expandable:false,
		  expanded:true,
		  id:'0'
	      });
	      
	arbolAcc.setRootNode(padreArbolAcc);
	
	////------------ Evento para habilitar botones ------------////
	arbolAcc.on('click', function (node, e){
		btnModificar.disable();
		btnEliminar.disable();
		btnAdicionar.disable();
                btnBuscar.disable();
                combocriterios.disable();
		storeGrid.removeAll();
		if (node.isLeaf())
		{
                    grid.enable();
                    idfuncionalidad = node.attributes.idfuncionalidad;
                    storeGrid.removeAll();
                    storeGrid.load({params:{start:0,limit:15}});
                    btnAdicionar.enable();
                    btnBuscar.enable();
                    combocriterios.enable();    
                    auxIns = true;
                    auxBus = true;
		}
		else
		{
			
			auxDel = false;
			auxBus = false;
			auxIns = false;
			auxMod = false;
		}
                if(node.id == 0)
                {
                    storeGrid.removeAll();
                    grid.disable();
                }
	}, this);
	
	////------------ Store del grid Accion ------------////
	var storeGrid =  new Ext.data.Store({
		url: 'cargargridacciones',
        listeners:{'beforeload':function(thisstore,objeto){
            objeto.params.idfuncionalidad = idfuncionalidad
                }},
            
			reader:new Ext.data.JsonReader({
				totalProperty: "cantidad_filas",
				root: "datos",
				id: "id"
				},
				[
					{name:'idaccion', mapping:'idaccion'},
					{name:'abreviatura', mapping:'abreviatura'},
					{name:'denominacion', mapping:'denominacion'},
					{name:'descripcion', mapping:'descripcion'},
					{name:'icono', mapping:'icono'}
				])
	});
	
	////------------ MODO DE SELECCION; SI singleSelect = true=> INDICA SELECCION SIMPLE ------------////
	var sm = new Ext.grid.RowSelectionModel({singleSelect:true});
	
	////------------ Para habilitar boton de modificar y eliminar ------------////
	sm.on('beforerowselect', function (smodel, rowIndex, keepExisting, record ){
				btnModificar.enable();
				btnEliminar.enable();
		}, this);
         criterioSel = combocriterios.getValue();       
	////------------ Grid de Acciones ------------////
	var grid = new Ext.grid.GridPanel({ 
		text:perfil.etiquetas.lbMsgGridAcc,   
		region:'center',
		frame:true,
		width:'40%',
                disabled:true,
		iconCls:'icon-grid',  
		margins:'2 2 2 -4',
		autoExpandColumn:'expandir',
		store:storeGrid,
		sm:sm,
		columns: [
					{hidden:true, hideable: false, dataIndex: 'idaccion'},
					{id:'expandir',header:perfil.etiquetas.lbCampoDenom, width:200, dataIndex: 'denominacion'},
					{header: perfil.etiquetas.lbCampoAbreviatura, width:150, dataIndex: 'abreviatura'},
					{header: perfil.etiquetas.lbDescripcion, width:200, dataIndex: 'descripcion'},
					{hidden:true,header: perfil.etiquetas.lbIcono, width:150, dataIndex: 'icono'}
		 		 ],
		loadMask:{store:storeGrid},
		
		tbar:[
                new Ext.Toolbar.TextItem({text:perfil.etiquetas.lbAccion}),
		accion = new Ext.form.TextField({
                    width:150, id: 'nombreaccion',
                    regex:/(^([a-zA-Z_])+([a-zA-Z0-9_]*))$/,
                    maskRe: /[a-zA-Z0-9_]/i,
                    regexText:perfil.etiquetas.MsgInvalidRegex
            }),
		new Ext.menu.Separator(),			
		btnBuscar,
                new Ext.menu.Separator(),
                new Ext.form.Label({html:perfil.etiquetas.comboname}),
                combocriterios,
                new Ext.menu.Separator()
		],
		
		 bbar:new Ext.PagingToolbar({
	            pageSize: 15,
	            id:'ptbaux',
	            store: storeGrid,
	            displayInfo: true,
	            displayMsg:  perfil.etiquetas.lbMsgPaginado,
	            emptyMsg: perfil.etiquetas.lbMsgEmpty
		})
	});
	////------------ Trabajo con el PagingToolbar ------------////
	Ext.getCmp('ptbaux').on('change',function(){
		sm.selectFirstRow();
	},this)	
	
	////------------ Panel ------------////
	var panel = new Ext.Panel({
		layout:'border',
		title:perfil.etiquetas.lbTitGestAccion,
		items:[arbolAcc,grid],
		tbar:[btnAdicionar,btnModificar,btnEliminar/*,btnAyuda*/],
		keys: new Ext.KeyMap(document,[{
		    			key:Ext.EventObject.DELETE,
		    			fn: function(){
		    			if(auxDel && auxDelete && auxDel2 && auxDel3)
		    				eliminarAccion();
		    			}
		    		  	},
		    		  	{
		    		  		key:"i",
		    				alt:true,
		    				fn: function(){
		    				if(auxIns && auxIns2 && auxIns3)
		    					winForm('Ins'); 
		    		  		}
		    		  	},
		    		  	{
		    		  		key:"b",
		    				alt:true,
		    				fn: function(){
		    				if(auxBus && auxBus3)
		    					buscaraccion(Ext.getCmp('nombreaccion').getValue());
	    					}
		    		 	},
		    		  	{
		    		  		key:"m",
		    				alt:true,
		    				fn: function(){
	    					if(auxMod && auxMod2 && auxMod3)
		    					winForm('Mod');
		    				}		    			
		    		  	}])
	});
	////---------- Eventos para hotkeys ----------////
	btnAdicionar.on('show',function(){
		auxIns2 = true;
	},this)
	btnEliminar.on('show',function(){
		auxDel2 = true;
	},this)
	btnModificar.on('show',function(){
		auxMod2 = true;
	},this)
	Ext.getCmp('nombreaccion').on('focus',function(){
		auxDelete = false;
	},this)
	Ext.getCmp('nombreaccion').on('blur',function(){
		auxDelete = true;
	},this)
	storeGrid.on('load',function(){
		if(storeGrid.getCount() != 0)
		{
			auxMod = true;
			auxDel = true;
		}
		else
		{
			auxMod = false;
			auxDel = false;
		}
	},this)
	////------------ Otros eventos ------------////
	////------------ ViewPort ------------////
	var vpGestSistema = new Ext.Viewport({
		layout:'fit',
		items:panel
	})
	
	////------------ Formulario ------------////
        //console.info(tipos);
	var regAccion = new Ext.FormPanel({
		labelAlign: 'top',
		frame:true,
		bodyStyle:'padding:5px 5px 0',
		items: [{
				layout:'column',
				items:[{
						columnWidth:.6,
						layout:'form',
						items:[{
								xtype:'textfield',
								fieldLabel:perfil.etiquetas.lbCampoDenom,
								id:'denominacion',
                                //maxLength:30,
								allowBlank:false,
					            blankText:perfil.etiquetas.lbMsgBlankTextDenom,
					            regex:tipos,
								regexText:perfil.etiquetas.lbMsgExpRegDenom,
								anchor:'95%'
							  }]
					   },{
						columnWidth: .1,
						layout:'form',
						items:[btnExplorar ]
					   },
					   {
							columnWidth:.3,
							layout: 'form',
							items: [{
									xtype:'textfield',
									fieldLabel: perfil.etiquetas.lbCampoAbreviatura,
									id:'abreviatura',
                                    //maxLength:30,
									allowBlank:false,
					            	blankText:perfil.etiquetas.lbMsgBlankTextDenom,
					            	regex:tipos,
									regexText:perfil.etiquetas.lbMsgExpRegDenom,
									anchor:'100%'
							       }]
					   },
					   {
							columnWidth:1,
							layout: 'form',
							items: [{
									xtype:'textarea',
									fieldLabel: perfil.etiquetas.lbDescripcion,
									id: 'descripcion',
									anchor:'100%'
							       }]
					   }]
			  }]
	});
	
	////------------ Cargar Ventanas ------------////
	function winForm(opcion){
		switch(opcion){
			case 'Ins':{
				if(!winIns)
				{
					winIns = new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						title:perfil.etiquetas.lbTitAdicionarAcc,width:400,height:230,
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
							handler:function(){adicionarAccion('apl');}
						},
						{	
							icon:perfil.dirImg+'aceptar.png',
							iconCls:'btn',
							text:perfil.etiquetas.lbBtnAceptar,
							handler:function(){adicionarAccion();}
						}]
					});
					winIns.on('show',function(){
						auxIns3 = false;
						auxMod3 = false;
						auxDel3 = false;
						auxBus3 = false;
					},this)
					winIns.on('hide',function(){
						auxIns3 = true;
						auxMod3 = true;
						auxDel3 = true;
						auxBus3 = true;
					},this)
				}
				winIns.add(regAccion);
                regAccion.getForm().reset();
				winIns.doLayout();
				winIns.show();
			}break;
			case 'Mod':{
				if(!winMod)
				{
					winMod= new Ext.Window({modal: true,closeAction:'hide',layout:'fit',
						title:perfil.etiquetas.lbTitModificarAcc,width:400,height:230,
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
							handler:function(){modificarAccion();}
						}]
					});
					winMod.on('show',function(){
						auxIns3 = false;
						auxMod3 = false;
						auxDel3 = false;
						auxBus3 = false;
					},this)
					winMod.on('hide',function(){
						auxIns3 = true;
						auxMod3 = true;
						auxDel3 = true;
						auxBus3 = true;
					},this)
				}
				winMod.add(regAccion);
                winMod.doLayout();
				winMod.show();
                regAccion.getForm().loadRecord(sm.getSelected());
                denMod = Ext.getCmp('denominacion').getValue();
                desMod = Ext.getCmp('descripcion').getValue();
                abrevMod = Ext.getCmp('abreviatura').getValue(); 
			}break;
		}
	}
	//}
		////------------ Arbol Acciones ------------////
	arbolSeleccaccion = new Ext.tree.TreePanel({
		title:perfil.etiquetas.lbTitArbolSelecaccion,
		collapsible:true,
		autoScroll:true,
		loader: new Ext.tree.TreeLoader({
			dataUrl:'buscarAccionesControlador',
			listeners:{'beforeload':function(atreeloader, anode){ 
							atreeloader.baseParams = {};	
								atreeloader.baseParams.idfuncionalidad = arbolAcc.getSelectionModel().getSelectedNode().attributes.idfuncionalidad;
								atreeloader.baseParams.referencia = arbolAcc.getSelectionModel().getSelectedNode().attributes.referencia;	
							
						}
					}
				
		})
	});
	////------------ Crear nodo padre del arbol ------------////
	padreArbolSelecc = new Ext.tree.AsyncTreeNode({
	      text:perfil.etiquetas.lbRootNodeArbolSelecaccion,
		  expandable:false,
		  expanded:true,
		  id:'0'
	      });
	arbolSeleccaccion.setRootNode(padreArbolSelecc);

   	arbolSeleccaccion.on('click', function (node, e){
        	if (node.id > 0)
		    {
		       Ext.getCmp('denominacion').setValue(node.attributes.text);
			winSelecaccion.hide();
		    }
            }, this);
	      
	    	
	function buscaraccionescontrolador() {
		if (!winSelecaccion) {
			winSelecaccion = new Ext.Window({
				modal: true,
				closeAction:'hide',
				layout:'fit',
				title:perfil.etiquetas.lbTitSeleccaccion,
				width:250,
				height:350,
				resizable:false
			});
			winSelecaccion.add(arbolSeleccaccion);
			winSelecaccion.doLayout();
		
		}
		winSelecaccion.show();
		arbolSeleccaccion.getRootNode().reload();
	}
	////------------ Adicionar Aciones ------------////
	function adicionarAccion(apl){
		if (regAccion.getForm().isValid()){
			regAccion.getForm().submit({
				url:'insertaraccion',
				waitMsg:perfil.etiquetas.lbMsgEsperaInsAcc,  
				params:{idfuncionalidad:arbolAcc.getSelectionModel().getSelectedNode().attributes.idfuncionalidad,idsistema:arbolAcc.getSelectionModel().getSelectedNode().parentNode.attributes.idsistema },
				failure: function(form, action){
					if(action.result.codMsg != 3){
						//mostrarMensaje(action.result.codMsg,perfil.etiquetas.lbMsgAddAct); 
						regAccion.getForm().reset(); 
						
						if(!apl) 
						winIns.hide();
						
						storeGrid.reload();
						sm.clearSelections();
						btnModificar.disable();
						btnEliminar.disable();
					}
//					if(action.result.codMsg == 3){
//					mostrarMensaje(action.result.codMsg,action.result.mensaje);
//					}
					
				}
			});
		}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);        
	}
	
	////------------ Modififcar Accion ------------////
	function modificarAccion(){
		if (regAccion.getForm().isValid())
		{
                  var dMod =  denMod != Ext.getCmp('denominacion').getValue();
                  var deMod = desMod != Ext.getCmp('descripcion').getValue();
                  var aMod = abrevMod != Ext.getCmp('abreviatura').getValue();
                  if(dMod||deMod||aMod){
			regAccion.getForm().submit({
				url:'modificaraccion',
				waitMsg:perfil.etiquetas.lbMsgEsperaModAcc, 
				params:{idaccion:sm.getSelected().data.idaccion,idfuncionalidad:arbolAcc.getSelectionModel().getSelectedNode().attributes.idfuncionalidad},
				failure: function(form, action){
					if(action.result.codMsg != 3)
					{
						//mostrarMensaje(action.result.codMsg,perfil.etiquetas.lbMsgModAct); 
						storeGrid.reload();
						winMod.hide();
					}
					//if(action.result.codMsg == 3) mostrarMensaje(action.result.codMsg,action.result.mensaje);
					
				}
			});
                }
                else
                 mostrarMensaje(3,perfil.etiquetas.NoModify);
		}
        else
            mostrarMensaje(3,perfil.etiquetas.lbMsgErrorEnCamops);        
	}
	
	////------------ Eliminar Accion ------------////
	function eliminarAccion(){
		mostrarMensaje(2,perfil.etiquetas.lbMsgDeseaEliminar,elimina);
		function elimina(btnPresionado){
			if (btnPresionado == 'ok')
			{
				Ext.Ajax.request({
					url: 'eliminaraccion',
					method:'POST',
					params:{idaccion:sm.getSelected().data.idaccion},
					callback: function (options,success,response){
							responseData = Ext.decode(response.responseText);
							if(responseData.codMsg == 1)
							{
								//mostrarMensaje(responseData.codMsg,perfil.etiquetas.lbMsgDelAct);
								storeGrid.reload();
								sm.clearSelections();
								btnModificar.disable();
								btnEliminar.disable();
							}
							//if(responseData.codMsg == 3) mostrarMensaje(responseData.codMsg,responseData.mensaje);
					}
				});
			}
		}
	}
	
	////------------ Buscar Accion ------------////
	 function buscaraccion(accion){  
		    storeGrid.load({params:{denominacion:accion,idfuncionalidad:arbolAcc.getSelectionModel().getSelectedNode().attributes.idfuncionalidad,start:0,limit:15}});
	    }
        ///---Codigo Agregado por Jose Eduardo
        
         ////------------ Ventana Para Traer los Objetos de la Base de Datos----////
     function levantarVentanaObjetosBD(){
        idaccion=sm.getSelected().data.idaccion;
        onclickcombo();
     }
     var servidorSelect;
     var gestorSelect;
     var bdSelect;
     var esquemaSelect;
     var nombreSelect;
     var checkSelect;
     var subsistemaSelect;
     var servicioSelect;
     function tbarGridDinamico(seleccion){
         
         if(seleccion!="Servicios"){
             return [
               servidorSelect = new Ext.form.TextField({
               width:80,emptyText:perfil.etiquetas.emptyTextServidor,id:'servidor',
               maskRe:/[0-9.]/i,
               enableKeyEvents:true,
               listeners:{
                      'keyup':function(servidorSelect,e){
                           var input=servidorSelect.getValue();
                          if(input!=LastValueServidor){
                           buscarSobreObjetos()
                           LastValueServidor=input;
                          }
                      }  
                }}),
                new Ext.menu.Separator(),
                gestorSelect = new Ext.form.TextField({
                width:80,emptyText:perfil.etiquetas.emptyTextGestor,id:'gestor',
                enableKeyEvents:true,
                maskRe:/[a-z]/i,
                listeners:{
                      'keyup':function(gestorSelect,e){
                            var input=gestorSelect.getValue();
                          if(input!=LastValueGestor){
                           buscarSobreObjetos()
                           LastValueGestor=input;
                          }
                      } 
                }}),
                new Ext.menu.Separator(),
                bdSelect = new Ext.form.TextField({
                width:80,emptyText:perfil.etiquetas.emptyTextBD,id:'bd',
                maskRe: /[a-zA-Z0-9_]/i,
                enableKeyEvents:true,
                listeners:{
                      'keyup':function(bdSelect,e){
                           var input=bdSelect.getValue();
                          if(input!=LastValueBD){
                           buscarSobreObjetos()
                           LastValueBD=input;
                          }
                      } 
                }}),
                new Ext.menu.Separator(),
                esquemaSelect = new Ext.form.TextField({
                width:109,emptyText:perfil.etiquetas.emptyTextEsquema,id:'esquemas',
                maskRe: /[a-zA-Z0-9_]/i,
                enableKeyEvents:true,
                listeners:{
                      'keyup':function(esquemaSelect,e){
                           var input=esquemaSelect.getValue();
                          if(input!=LastValueEsquema){
                           buscarSobreObjetos()
                           LastValueEsquema=input;
                          }
                      } 
                }}),
                new Ext.menu.Separator(),
                nombreSelect = new Ext.form.TextField({
                width:144,emptyText:perfil.etiquetas.emptyTextObjeto,id:'nombreObj',
                maskRe: /[a-zA-Z0-9_]/i,
                enableKeyEvents:true,
                listeners:{
                      'keyup':function(nombreSelect,e){
                           var input=nombreSelect.getValue();
                          if(input!=LastValueEntidad){
                           buscarSobreObjetos()
                           LastValueEntidad=input;
                          }
                      }
                }}),
               new Ext.menu.Separator(),
               checkSelect=new Ext.form.Checkbox({
                   id:'checkselecteds',
                   boxLabel:perfil.etiquetas.checkBoxLabel,
                   listeners:{
                       'check':function(checkSelect,c){
                           buscarSobreObjetos();
                       }
                   }
           })]
         }
         else{
            return[
                 subsistemaSelect = new Ext.form.TextField({
                width:109,emptyText:perfil.etiquetas.emptyTextSubsistema,id:'sbsistema',
                maskRe: /[a-zA-Z0-9_]/i,
                enableKeyEvents:true,
                listeners:{
                      'keyup':function(subsistemaSelect,e){
                           var input=subsistemaSelect.getValue();
                          if(input!=LastValueSubsistema){
                           buscarSobreObjetos()
                           LastValueSubsistema=input;
                          }
                      } 
                }}),
                new Ext.menu.Separator(),
                servicioSelect = new Ext.form.TextField({
                width:144,emptyText:perfil.etiquetas.emptyTextServicio,id:'servicio',
                maskRe: /[a-zA-Z0-9_]/i,
                enableKeyEvents:true,
                listeners:{
                      'keyup':function(servicioSelect,e){
                          var input=servicioSelect.getValue();
                          if(input!=LastValueServicio){
                           buscarSobreObjetos()
                           LastValueServicio=input;
                          }
                      } 
                }}),
               new Ext.menu.Separator(),
               checkSelect=new Ext.form.Checkbox({
                   id:'checkselecteds',
                   boxLabel:perfil.etiquetas.checkBoxLabel,
                   listeners:{
                       'check':function(checkSelect,c){
                           buscarSobreObjetos();
                       }
                   }
           })
               //new Ext.form.Label({html:'<pre> Seleccionados<pre>'})
             ]
         }
     }
     ////-------------onclick Combo-------------////
     function onclickcombo(){
        smgestion = new Ext.grid.RowSelectionModel({singleSelect : true});
        /////-------------Store del grid dinamico(de los objetos)---------------////////////
        criterioSel = combocriterios.getValue();
      var boton;
       if(criterioSel!="Servicios")
        boton=[{
                icon:perfil.dirImg+'cancelar.png',
                iconCls:'btn',
                handler:function(){auxDelShowW=true;winCrt.close();},
                text:perfil.etiquetas.lbBtnCerrar
                },{
                icon:perfil.dirImg+'aceptar.png',
                iconCls:'btn',
                handler:function(){asignar();},
                text:perfil.etiquetas.lbBtnSalvar
                },{
                icon:perfil.dirImg+'aceptar.png',
                iconCls:'btn',
                handler:function(){mostrarIpsError();},
                text:perfil.etiquetas.lbBtnIpsError
            }];
        else{
            boton=[{
                icon:perfil.dirImg+'cancelar.png',
                iconCls:'btn',
                handler:function(){auxDelShowW=true;winCrt.close();},
                text:perfil.etiquetas.lbBtnCerrar
                },{
                icon:perfil.dirImg+'aceptar.png',
                iconCls:'btn',
                handler:function(){asignar();},
                text:perfil.etiquetas.lbBtnSalvar
                }]; 
        }
        //--------Ventana de los Objetos--------   
        winCrt = new Ext.Window({
            modal: true,
            closeAction:'hide',
            layout:'fit',
            width:870,
            height:585,
            resizable:false,
            draggable:false,
            region: 'center',

            buttons:boton
        });  
        var tbar=tbarGridDinamico(criterioSel);
        cmGestionhist = new Ext.grid.ColumnModel([{id : 'expandir',autoExpandColumn : 'expandir'}]);
              
        storegridObjetos = new Ext.data.Store({
            url : '',            
            reader : new Ext.data.JsonReader({
                totalProperty : "totalProperty",
                root : "root",
                messageProperty:"mensaje"
            }, [{
                name : 'vacio'
            }])
        }); 
        
        var PagingToolBar= new Ext.PagingToolbar({store : storegridObjetos,displayInfo : true,pageSize : 15})       
        gdGestionHis = new Ext.grid.EditorGridPanel({
            frame : true,
            sm : smgestion,
            clicksToEdit:10,
            store : storegridObjetos,
            autowidth:true,
            visible: true,
            title:perfil.etiquetas.lbTitAsignarPermiso,
            loadMask : {msg : perfil.etiquetas.lbMsgEspera},
            cm : cmGestionhist,
            tbar :tbar,
            bbar : PagingToolBar,   
            listeners:{
                'cellclick': function (grid,rowIndex,columnIndex,e){
                    var record = grid.getStore().getAt(rowIndex);  // Get the Record
                    var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
                    var data = record.get(fieldName);
                    storegridObjetos.getAt(rowIndex).set(newcm.getColumnHeader(columnIndex),!data);
                    if(criterioSel!="Servicios")
                    CampiarEstadoFilaColumna(rowIndex, columnIndex-11,record)
                    else
                    CampiarEstadoFilaColumna(rowIndex, columnIndex-3,record)
                   var own="OWN";var i= 11
                    if(fieldName!=own){
                    data = record.get(own);
                    if(data){
                    storegridObjetos.getAt(rowIndex).set(newcm.getColumnHeader(i),false);
                    CampiarEstadoFilaColumna(rowIndex, i-11,record);
                    }
                    }
                    else if(criterioSel!="Servicios") {
                        var length=newcm.getColumnCount()
                        while(i<length-1){
                          i++;
                          fieldName = newcm.getDataIndex(i);
                          data = record.get(fieldName);
                        if(data){
                        storegridObjetos.getAt(rowIndex).set(newcm.getColumnHeader(i),false);
                        CampiarEstadoFilaColumna(rowIndex, i-11,record);
                        }
                        }
                    }
                   
                    
                }}});
        Ext.Ajax.request({
            url: 'configridObjetos',
            method:'POST',
            params:{criterio: criterioSel},
            callback: function (options,success,response){
                responseData = Ext.decode(response.responseText);
                camposGridDinamico = responseData.grid.campos; 
                var i=0;
                if(criterioSel!="Servicios")i = 11;else  i=3;
                
                while( i < responseData.grid.columns.length){
                    var aux = responseData.grid.columns[i];
                    responseData.grid.columns[i].editor = new Ext.form.Checkbox({checked:false});
                    aux.renderer = function (data,cell, record, rowIndex, columnIndex,store){ 
                        if (data){
                            return "<img src='../../../../images/icons/validado.png' />";
                        }
                        else{
                            return "<img src='../../../../images/icons/no_validado.png' />";
                        }
                    }
                    i++
                }
                                
                Ext.UCID.idiomaHeader(responseData.grid.columns,perfil.etiquetas);               
                
                newcm = Ext.UCID.generaDinamico('cm', responseData.grid.columns);

                storegridObjetos = new Ext.data.Store({
                    url : 'cargargridObjetos',
                    listeners : {
                        'beforeload' : function() {
                            gdGestionHis.getSelectionModel().selectFirstRow();
                        }
                    },
                    pruneModifiedRecords:true,
                    reader : new Ext.data.JsonReader({
                        totalProperty: 'cantidad',
                        root : 'datos',
                        id : 'iddatos',
                        messageProperty:'mensaje'
                    },Ext.UCID.generaDinamico('rdcampos', responseData.grid.campos)
                        )

                });
               
              
                var menu = new Ext.menu.Menu({
                    id:'submenu',
                    items:[{
                        text:perfil.etiquetas.menuTextSelectAllFile,
                        scope: this,
                        icon: "../../../../images/icons/añadir.png",
                        handler:function(){
                             var j=0;
                            if(criterioSel!="Servicios")j = 11;else  j=3;
                            while( j < newcm.getColumnCount()){
                                    storegridObjetos.getAt(fila).set(newcm.getColumnHeader(j),true);
                                    var record =storegridObjetos.getAt(fila);
                                    if(criterioSel!="Servicios")
                                    CampiarEstadoFilaColumna(fila, j-11,record);
                                else
                                    CampiarEstadoFilaColumna(fila, j-3,record);
                                     j++
                            }
                            
                        }
                    },
                    {
                        text:perfil.etiquetas.menuTextSelectAllColumn,
                        scope: this,
                        icon: "../../../../images/icons/añadir.png",
                        handler:function(){
                            for(var i = 0; i < storegridObjetos.getCount(); i++){
                                var record =storegridObjetos.getAt(fila);
                                storegridObjetos.getAt(i).set(newcm.getColumnHeader(col),true);
                                if(criterioSel!="Servicios")
                                CampiarEstadoFilaColumna(i, col-11,record);
                                else
                                CampiarEstadoFilaColumna(i, col-3,record);
                            }
                        }
                    },
                    {
                        text:perfil.etiquetas.menuTextUnCheckAllFile,
                        scope: this,
                        icon: "../../../../images/icons/eliminar.png",
                        handler:function(){
                             var j=0;
                            if(criterioSel!="Servicios")j = 11;else  j=3;
                            while( j < newcm.getColumnCount()){
                                    storegridObjetos.getAt(fila).set(newcm.getColumnHeader(j),false);
                                    var record =storegridObjetos.getAt(fila);
                                    if(criterioSel!="Servicios")
                                    CampiarEstadoFilaColumna(fila, j-11,record);
                                else
                                    CampiarEstadoFilaColumna(fila, j-3,record);
                                     j++
                            }
                            
                        }
                    },
                    {
                       text:perfil.etiquetas.menuTextUnCheckAllColumn,
                        scope: this,
                        icon: "../../../../images/icons/eliminar.png",
                        handler:function(){
                            for(var i = 0; i < storegridObjetos.getCount(); i++){
                                storegridObjetos.getAt(i).set(newcm.getColumnHeader(col),false);
                                var record =storegridObjetos.getAt(fila);
                                if(criterioSel!="Servicios")
                                CampiarEstadoFilaColumna(i, col-11,record);
                                else
                                CampiarEstadoFilaColumna(i, col-3,record);
                            }
                        }
                    }]
                });
        
                gdGestionHis.on('cellcontextmenu', function( _this, rowIndex, cellIndex, e){
                    fila = rowIndex;
                    col = cellIndex;
                    smgestion.selectRow(fila);
                    e.stopEvent();
                    menu.showAt(e.getXY());
                },this);


                if (newcm && storegridObjetos){
                    gdGestionHis.reconfigure(storegridObjetos, newcm);
                    gdGestionHis.getBottomToolbar().bind(storegridObjetos);
                    
                    
                    storegridObjetos.on('beforeload', function(s){
                     if(criterioSel!="Servicios"){
                    servidorSeleccionado=servidorSelect.getValue();
                    gestorSeleccionado=gestorSelect.getValue();
                    bdSeleccionada=bdSelect.getValue();
                    esquemaSeleccionado=esquemaSelect.getValue();
                    objetoSeleccionado=nombreSelect.getValue();
                   
                    }
                    else{
                    subsistemaSeleccionado=subsistemaSelect.getValue();
                    servicioSeleccionado=servicioSelect.getValue();
                    }
               if(criterioSel!="Servicios"){
                storegridObjetos.baseParams = { 
                        
                        servSelected:servidorSeleccionado,gestSelected:gestorSeleccionado,
                        bdSelected:bdSeleccionada,esqSelected:esquemaSeleccionado,
                        nombSelected:objetoSeleccionado,criterio:criterioSel,
                        idfunc:idfuncionalidad,idacc:idaccion,
                        seleccionados:Ext.getCmp('checkselecteds').getValue()
                 };
               }else{
                   storegridObjetos.baseParams = { 
                        servicioSelected:servicioSeleccionado,
                        subsistSelected:subsistemaSeleccionado,
                        idfunc:idfuncionalidad,idacc:idaccion,
                        criterio:criterioSel,
                        seleccionados:Ext.getCmp('checkselecteds').getValue()
                 };
               }
                      });
                      
                    storegridObjetos.load({
                        params : {
                        start : 0,limit : 15,idacc:idaccion,criterio:combocriterios.getValue(),
                        seleccionados:Ext.getCmp('checkselecteds').getValue()
                        }
                    });
                    storegridObjetos.on('load', function(s){
                    ReiniciarFilasColumnas();
                    
                });
//                PagingToolBar.on('beforechange',function(_this,ParamsToSend){
//                
//                    if(IsModificados()){
//                    if(confirm('Confirmación','¿Desea salvar los cambios antes de continuar?'))                       
//                     asignar();
//                    }
//                    
//                });
                }
                
                combocriterios.clearValue();
            }
        });
   
        winCrt.add(gdGestionHis);
        winCrt.doLayout();
        winCrt.show();
        auxDelShowW=false;
        Ext.getBody().unmask();
    
     }
         
     function CampiarEstadoFilaColumna(fila,columna,record){
            
            if(IsPosicionModificado(fila, columna)){
                pop(fila, columna);
            }
            else
                push(fila, columna)
        }
        
        function push(fila,columna){
            FilasColumnasModificadas[fila][columna]=1;
        }
        function pop(fila,columna){
            FilasColumnasModificadas[fila][columna]=0;
        }
        function IsPosicionModificado(fila,columna){
            if(FilasColumnasModificadas[fila][columna]==1)
            return true;
        return false;
        }
        
        function IsModificados(){
            for(var i=0; i<FilasColumnasModificadas.length;i++){
                for (var j=0; j<FilasColumnasModificadas[i].length;j++){
                    if(FilasColumnasModificadas[i][j]==1)
                        return true;
                }
            }
        return false;
        }
        
        function ReiniciarFilasColumnas(){
               FilasColumnasModificadas=new Array();
                       var TotalColumn=newcm.getColumnCount();
                       var TotalFila=storegridObjetos.getCount();
                       var length=TotalColumn-3;
                       if(criterioSel!="Servicios")
                           length=TotalColumn-11;
                       for(var i=0 ;i<TotalFila;i++){
                          var columnas=new Array();
                          for(var j=0 ;j<length;j++){
                           columnas.push(0);
                       }
                          FilasColumnasModificadas.push(columnas);
                       }
                }  
       ////---------------Asignar los permisos para la base de datos-----------------------////
    function asignar() {
        var filasModificadas = storegridObjetos.getModifiedRecords();
        var cantFilas = filasModificadas.length;
        var cmHis = gdGestionHis.getColumnModel();
        var cantCol = cmHis.getColumnCount();
        var arrayAcceso = [];
        var arrayDenegado = [];
         var criterio=criterioSel.toLowerCase();
          if(IsModificados()){
             Ext.Msg.wait(perfil.etiquetas.MsgSalvandoDatos,perfil.etiquetas.TitleWaitMsg); 
         if(criterio!="servicios"){                
        for (var i = 0; i < cantFilas; i++) {
            var colsFila = filasModificadas[i].getChanges();            
            var nameFila = filasModificadas[i].data[criterio];
            var idservidor=filasModificadas[i].data.idservidor;
            var servidor=filasModificadas[i].data.servidor;
            var idgestor=filasModificadas[i].data.idgestor;
            var gestor=filasModificadas[i].data.gestor;
            var idbd=filasModificadas[i].data.idbd;
            var bd=filasModificadas[i].data['base de datos'];
            var idesquema=filasModificadas[i].data.idesquema;
            var esquema=filasModificadas[i].data.esquema;
            var idrol=filasModificadas[i].data.idrol;
            var idobjetobd=filasModificadas[i].data.idobjetobd;   
            var arrayColAut = [];
            var arrayColDen = [];
            var indexOf=storegridObjetos.indexOf(filasModificadas[i]);
            for (var j = 1; j <= cantCol; j++) {
                if(j>10&&FilasColumnasModificadas[indexOf][j-11]==1){         
              var  nameCampo = camposGridDinamico[j];                               
                var cadEval = 'colsFila.' + nameCampo;
                if(nameCampo!='base de datos')
                    var valCol = eval(cadEval);     
                if (valCol == true)
                    arrayColAut.push(nameCampo);
                else if (valCol == false)
                    arrayColDen.push(nameCampo);
                }
            }
            if (arrayColAut.length)
                arrayAcceso.push([idservidor,idgestor,idbd,idesquema,idrol,nameFila,arrayColAut,idobjetobd,servidor,gestor,bd,esquema]);
            if (arrayColDen.length)
                arrayDenegado.push([idservidor,idgestor,idbd,idesquema,idrol,nameFila,arrayColDen,idobjetobd,servidor,gestor,bd,esquema]);
        }
        
        jsonAcceso = Ext.encode(arrayAcceso);
        jsonDenegado = Ext.encode(arrayDenegado);
       }else{
           
           for (var is = 0; is < cantFilas; is++) {           
            var subsistema=filasModificadas[is].data.subsistema;
            var servicio=filasModificadas[is].data.servicio;
            var idservicio=filasModificadas[is].data.idservicio;                                                            
             colsFila = filasModificadas[is].getChanges();
              nameCampo = camposGridDinamico[3];                               
              cadEval = 'colsFila.' + nameCampo;
              var valCols=eval(cadEval);
          if (valCols==true)
                arrayAcceso.push([subsistema,idservicio,servicio]);
           else if(valCols==false) 
                arrayDenegado.push([subsistema,idservicio,servicio]);
            }
            
        
        jsonAcceso = Ext.encode(arrayAcceso);
        jsonDenegado = Ext.encode(arrayDenegado);
       }
       for(var h=0;h<cantFilas;h++)
       filasModificadas[h].modified=[];
        Ext.Ajax.request({
            url: 'modificarPermisos',
            method: 'POST',
            params: {
                acceso: jsonAcceso,
                denegado: jsonDenegado,
                idfuncionalidad:idfuncionalidad,
                idaccion:idaccion,
                criterio: criterioSel

            },
            callback: function(options, success, response){
                responseData = Ext.decode(response.responseText);
                if (responseData.codMsg == 1) {
                    if(criterio!="servicios"){
                    var eliminados=responseData.elim;
                    eliminados=Ext.decode(eliminados);
                    var adicionados=responseData.add;
                    adicionados=Ext.decode(adicionados);
                    IdEliminados0(eliminados, filasModificadas);
                    IdAdicionadosX(adicionados,filasModificadas,criterio)
                    Ext.Msg.hide();
                    //mostrarMensaje(responseData.codMsg, perfil.etiquetas.lbMsgInfPermisos);
                    }else{
                        storegridObjetos.reload();
                    }
                }
                if (responseData.codMsg == 3)
                    Ext.Msg.hide();
                   // mostrarMensaje(responseData.codMsg, responseData.mensaje);
            }
        });
          ReiniciarFilasColumnas();
    }
    else{
        mostrarMensaje(3,perfil.etiquetas.NoModify);
    }
     storegridObjetos.rejectChanges();
    }
    
    function IdAdicionadosX(add,modificadas,criterio){
        for(var i=0; i<add.length;i++){
            CambiarIdObj(add[i], modificadas,criterio)
        }
    }
    function CambiarIdObj(obj,modificadas,criterio){
        for(var i=0; i<modificadas.length;i++){
            var nameFila = modificadas[i].data[criterio];
            var idservidor=modificadas[i].data.idservidor;
            var idgestor=modificadas[i].data.idgestor;
            var idbd=modificadas[i].data.idbd;
            var idesquema=modificadas[i].data.idesquema;
            if(idservidor==obj.idservidor&&idgestor==obj.idgestor&&idbd==obj.idbd&&
                idesquema==obj.idesquema&&nameFila==obj.obj){
                modificadas[i].data.idobjetobd=obj.idobj;
                }
        }
    }
    function IdEliminados0(eliminados,modificadas){
        for(var i=0;i<modificadas.length; i++){
            if(IsEliminado(modificadas[i].data.idobjetobd, eliminados))
                modificadas[i].data.idobjetobd=0;
        }
    }
    function IsEliminado(id,eliminados){
        for(var i=0;i<eliminados.length; i++){
            if(eliminados[i]==id)
                return true;
        }
    return false;
    }
    
    function mostrarIpsError(){
        Ext.Ajax.request({
            url: 'mostrarMensaje',
            method:'POST',
            callback: function (options,success,response){
                responseData = Ext.decode(response.responseText);
                if (responseData.bien == 1) {
                    var Msg=responseData.Msg;                    
                mostrarMensaje(1,responseData.mensaje+Msg);
                }
            }
        });
    }  
    
    function buscarSobreObjetos(){
        
        
         if(criterioSel!="Servicios"){
            servidorSeleccionado=servidorSelect.getValue();
                    gestorSeleccionado=gestorSelect.getValue();
                    bdSeleccionada=bdSelect.getValue();
                    esquemaSeleccionado=esquemaSelect.getValue();
                    objetoSeleccionado=nombreSelect.getValue();
        }
         else{
                    subsistemaSeleccionado=subsistemaSelect.getValue();
                    servicioSeleccionado=servicioSelect.getValue();
                    }
         
   
        storegridObjetos.load({
            params:{
                servSelected:servidorSeleccionado,
                gestSelected:gestorSeleccionado,
                bdSelected:bdSeleccionada,
                esqSelected:esquemaSeleccionado,
                nombSelected:objetoSeleccionado,
                servicioSelected:servicioSeleccionado,
                subsistSelected:subsistemaSeleccionado,
                idacc:idaccion,
                seleccionados:Ext.getCmp('checkselecteds').getValue(),
                start:0,
                limit:15
            }
        });
        
   }   
   
}
