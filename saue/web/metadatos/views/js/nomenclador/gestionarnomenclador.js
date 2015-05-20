var perfil = window.parent.UCID.portal.perfil

this.UCID.portal.cargarEtiquetas('gestionarnomenclador',gestionarNomenclador);
function gestionarNomenclador(){


	var wn_FormNomenclador; //usada para crear la ventana
	
	var WinTreeEspecialidad; //para la ventana  especialidad
	
	var abrirTreeEspecialidad;// usada para 
	
	var rec,ArbolDPAloader,raizArbolDPA ; // usada para guardar el record del grid
	var idpadre;
	var referencia, nombre, AddMod; // guarda parate de la url
	
	var url;  // guarada la url completa
	
	var params,nomencladores,arbolDPA, activo; // guarada los parametros que se le pasan 
	
	var st_Nomenclador; // usada para crear el store
	
	var gd_Nomenclador; // usada para crear grid
	
	var bt_AdicionarNomenclador,bt_ModificarNomenclador,// usada para crear
	
	bt_EliminarNomenclador,bt_AyudaNomenclador;  // los botones
	
	var item; // usada para aignarle el item de cada objeto
	
	var objectid; // usada para asignarle el id del objeto
	
	var object; //uasada para guad=rdar el objeto
	
	var fm_Nomenclador, // usada para crear el formulario
	
	    cm_Nomenclador, //usada para asiganar las columnas del objeto
	    
	    rc_Nomenclador;// usada para asignarle el record del objeto   
	 

	var nomencladores =[
						{ iconCls :'nodo',text: perfil.etiquetas.lbOrgano, leaf: true, referencia : 'organos',nombre:perfil.etiquetas.lborganonombre, id : 'idorgano'},
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbSubcategoria, leaf: true, referencia: 'sbcategoria',nombre:perfil.etiquetas.lbsubcategnombre, id: 'idsbcategorias' },
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbnivelstr, leaf: true, referencia: 'nvelestr',nombre:perfil.etiquetas.lbnivelstrnombre,id: 'idnvelestr' },
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbTipocifra, leaf: true, referencia: 'tipocifra', nombre:perfil.etiquetas.lbtipocifranombre,id: 'idtipocifra'},
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbCargocivil, leaf: true, referencia: 'cargocivil',nombre:perfil.etiquetas.lbcargocivilnombre, id: 'idcargociv'},
           		   		{ iconCls :'nodo',text: 'Calificador de cargos', leaf: true, referencia: 'calificador',nombre:perfil.etiquetas.lbcalificadornombre, id: 'idcalificador'},
						{ iconCls :'nodo',text: perfil.etiquetas.lbCargomilitar, leaf: true, referencia: 'cargomtar',nombre:perfil.etiquetas.lbcargomilitarnombre, id: 'idcargomilitar'},
           		   		{ iconCls :'nodo',text: 'Responsabilidad', leaf: true, referencia: 'catgriacvil', nombre:perfil.etiquetas.lbcatcivilnombre, id: 'idcatgriacvil'},
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbCategoriaocupacional, leaf: true, referencia: 'catgriaocpnal',nombre:perfil.etiquetas.lbcategocupanombre, id: 'idcatgriaocpnal'},
           		   		//{ iconCls :'nodo',text: perfil.etiquetas.lbespec, leaf: true, referencia: 'esp',nombre:perfil.etiquetas.lbespecialidadnombre, id: 'idesp'},
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbescalasalarial, leaf: true, referencia: 'escalasalarial',nombre:perfil.etiquetas.lbescalasalarialnombre, id: 'idescalasalarial'},
						{ iconCls :'nodo',text: perfil.etiquetas.lbgrupocomple, leaf: true, referencia: 'grupocomplejidad',nombre:perfil.etiquetas.lbgrupocomplenombre, id: 'idgrupocomplejidad'},
						//{ iconCls :'nodo',text: perfil.etiquetas.lbprefijo, leaf: true, referencia: 'prefijo',nombre:perfil.etiquetas.lbprefijonombre, id: 'idpref'},
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbsalario, leaf: true, referencia: 'salario',nombre:perfil.etiquetas.lbsalarionombre, id: 'idsalario'},
						{ iconCls :'nodo',text: perfil.etiquetas.lbTecnica, leaf: true, referencia: 'ntecnica',nombre:perfil.etiquetas.lbtecnicanombre, id: 'idtecnica'},
           		   		//{ iconCls :'nodo',text: perfil.etiquetas.lbModulo, leaf: true, referencia: 'nmodulo',nombre:perfil.etiquetas.lbmodulonombre, id: 'idmodulo'},
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbNivelutilizacion, leaf: true, referencia: 'nivelutilizacion',nombre:perfil.etiquetas.lbnivelnombre, id: 'idnivelutilizacion'},
						{ iconCls :'nodo',text: perfil.etiquetas.lbPreparacionmilitar, leaf: true, referencia: 'pmilitar',nombre:perfil.etiquetas.lbpremilitarnombre, id: 'idprepmilitar'},
           		   		{ iconCls :'nodo',text: 'Agrupación lógica', leaf: true, nombre:perfil.etiquetas.lbagrupnombre, referencia: 'nagrupacion', id: 'idagrupacion'},
           		   		{ iconCls :'nodo',text: perfil.etiquetas.lbGradomilitar, leaf: true, referencia: 'gradomilitar',nombre:perfil.etiquetas.lbgradomilitarnombre, id: 'idgradomilit'},
						/*{ iconCls :'nodo',text: perfil.etiquetas.lbIcono, leaf: true, referencia: 'icono',nombre:perfil.etiquetas.lbicononombre, id: 'idicono'},
						{ iconCls :'nodo',text: perfil.etiquetas.lbtipoDPA, leaf: true, referencia: 'tipodpa',nombre:perfil.etiquetas.lbtipoDPAnombre, id: 'idtipodpa'},
						{ iconCls :'nodo',text: perfil.etiquetas.lbDPA, leaf: true, referencia: 'dpa',nombre:perfil.etiquetas.lbDPAnombre, id: 'iddpa'},*/
						{ iconCls :'nodo',text: perfil.etiquetas.lbTipoCalif, leaf: true, referencia: 'tipocalificador',nombre:perfil.etiquetas.lbtipocalifnombre, id: 'idtipocalificador'},
						{ iconCls :'nodo',text: 'Tipo de plantilla', leaf: true, referencia : 'clasificacion',nombre:'tipo de plantilla', id : 'idclasificacion'}
           		   		
           		   		];  

	var tree  = new Ext.tree.TreePanel({
                 region: 'west',
                 collapsible: true,
                 title: perfil.etiquetas.lbTitSeleccione/*'Seleccione '*/,
                 width: 200,
                 autoScroll: true,
                 split: true,
                 minWidth:150,
                 loader: new Ext.tree.TreeLoader(),
                 root: new Ext.tree.AsyncTreeNode({
                 			text: perfil.etiquetas.lbNomencladores/*'Nomencladores'*/,
                 			id:'nomencladores',
                       		expanded: true,
                       		children: nomencladores
                       }),
                 rootVisible: false,
                 listeners: {click: On_TreeClick}
                 });

		var sm_MostrarNomenclador = new Ext.grid.RowSelectionModel({
										 singleSelect:false,
										 listeners: {rowselect: On_RowClick}
									});	

		var panel1 = new Ext.Panel({
							region:'center',
		        			height:350,
		        			width:700,
							disabled:true,
		        			layout:"fit"
		    		});
		var viewport = new  Ext.Viewport({
					            layout:'border',
					            items:[tree,panel1]
					        });

		/**VARIABLES PUBLICAS*/
		this.id;
		this.title_WinGestionarNomenclador;
	
		/**FUNCIONES PRIVADAS*/
		function ComprobarNomencladores(aurl,n)
		{
		  var url=aurl;	
          if(url) 
		  
		  Ext.Ajax.request({
				url:url,
				method:'POST',				
				callback: function (options,success,response){				
				
						responseData = Ext.decode(response.responseText);						
						if(responseData.text)
						{	
                              if(responseData.text.length==0)
                                {							  
									   
										object.cargarStore();
      									item = object.item;
								   	 	cm_Nomenclador=object.cm;
								   	 	rc_Nomenclador = object.rc;
								   	 	referencia = n.attributes.referencia;
								   	 	nombre = n.attributes.nombre;
								   	 	objectid = n.id;
								   	 	panel1.remove(gd_Nomenclador,true);
							   	 	if(n.id == 'iddpa'){
								   	 	
								   	 	panel1.add(arbolDPA);
								   	 	panel1.doLayout();
								   	 	panel1.enable();
								   	 	activo = true;
								   	 	
								   	 }
								   	else{
								   		
								   	 	CrearStore();
								   	 	CrearGrid(n);
								   	 	panel1.add(gd_Nomenclador);
								   	 	panel1.doLayout();
								   	 	panel1.enable();
								   	 	st_Nomenclador.load({params:{start:0, limit:20}});
								   	 	//marca();
								   	 	
								   	}
						
						}
						
						}
						
						
				}										
										});
						else
                          {
      									item = object.item;
								   	 	cm_Nomenclador=object.cm;
								   	 	rc_Nomenclador = object.rc;
								   	 	referencia = n.attributes.referencia;
								   	 	nombre = n.attributes.nombre;
								   	 	objectid = n.id;
								   	 	panel1.remove(gd_Nomenclador,true);
							   	 	if(n.id == 'iddpa'){
								   	 	
								   	 	panel1.add(arbolDPA);
								   	 	panel1.doLayout();
								   	 	panel1.enable();
								   	 	activo = true;
								   	 	
								   	 }
								   	else{
								   		
								   	 	CrearStore();
								   	 	CrearGrid(n);
								   	 	panel1.add(gd_Nomenclador);
								   	 	panel1.doLayout();
								   	 	panel1.enable();
								   	 	st_Nomenclador.load({params:{start:0, limit:20}});
								   	 	//marca();
								   	}						
										}
										
		}
		function On_TreeClick(n){
		var url;
		   	 		switch(n.id){
			   	 		case 'idorgano':
			   	 			 object = new NomencladorOrgano();
							 url='verificarcomboorgano';
			   	 		break;
			   	 		case 'idsbcategorias':
			   	 			 object = new NomencladorSubCategoria();
			   	 		break;
			   	 		case 'idnvelestr':
			   	 			 object = new NomencladorNivelEstructural();
			   	 		break;
			   	 		case 'idtipocifra':
			   	 			object = new NomencladorTipoCifra();
			   	 		break;
			   	 		case 'idcargociv':
			   	 			 object = new NomencladorCargoCivil();
							  url='verificarcombocargocivil';
			   	 		break;
			   	 		case 'idcargomilitar':
			   	 			 object = new NomencladorCargoMilitar();
							  url='verificarcombocargomilitar';
			   	 		break;
			   	 		case 'idtipodpa':
			   	 			 object = new NomencladorTipoDPA();
			   	 		break;
			   	 		case 'iddpa':
			   	 			 object = new NomencladorDPA();
			   	 		break;
			   	 		case 'idcatgriacvil':
			   	 			 object = new NomencladorCategoriaCivil();
			   	 		break;
			   	 		case 'idcatgriaocpnal':
			   	 			object = new NomencladorCategoriaOpcnal();
			   	 		break;
			   	 		case 'idesp':
			   	 			 object = new NomencladorEspecialidad();
			   	 		break;	
			   	 		case 'idpref':
			   	 			 object = new NomencladorPrefijo();
			   	 		break;
			   	 		case 'idtecnica':
			   	 			 object = new NomencladorTecnica();
			   	 		break;
			   	 		case 'idmodulo':
			   	 			 object = new NomencladorModulo();
			   	 		break;
			   	 		case 'idprepmilitar':
			   	 			 object = new NomencladorPreparacionMilitar();
			   	 		break;
			   	 		case 'idagrupacion':
			   	 			 object = new NomencladorAgrupacion();
			   	 		break;
			   	 		case 'idgradomilit':
			   	 			 object = new NomencladorGradoMilitar();
							 url='verificarcombogradomilitar';
			   	 		break;
						case 'idcalificador':
						     object = new NomencladorCalificador();
							 url='verificarcomboclasificadordecargos';
					    break;
					    case 'idtipocalificador':
						     object = new NomencladorTCalificador();
					    break;
					    
                        case 'idescalasalarial':
						     object = new NomencladorEscalaSalarial();
					    break;
						case 'idgrupocomplejidad':
						     object = new NomencladorGrupoComplejidad();
					    break;
						case 'idnivelutilizacion':
						     object = new NomencladorNivelUtilizacion();
					    break;
						case 'idsalario':
						     object = new NomencladorSalario();
							 url='verificarcombosalario';
					    break
						case 'idicono':
						     object = new NomencladorIcono();
					    break
						case 'idclasificacion':
						     object = new NomencladorClasificacion();
					    break
						
						
						
		   	 		}
					ComprobarNomencladores(url,n)
			   	 		
   	 		};
	
   	 	
   	 	

							   
   	 	
   	 		
		bt_AdicionarNomenclador = new Ext.Button({icon:perfil.dirImg+'adicionar.png',iconCls:'btn',id:"Adicionarpsto",												 
									  handler:Onbt_AdicionarNomencladorClick,
									  text:perfil.etiquetas.lbBtnAdicionar
								});
		bt_ModificarNomenclador = new Ext.Button({icon:perfil.dirImg+'modificar.png',iconCls:'btn',id:"Modificarpsto",disabled:true,										 
									  handler:Onbt_ModificarNomencladorClick,
									  text:perfil.etiquetas.lbBtnModificar
								});
		bt_EliminarNomenclador = new Ext.Button({icon:perfil.dirImg+'eliminar.png',iconCls:'btn',id:"Eliminarpsto",disabled:true,												 
									 handler:Onbt_EliminarNomencladorClick,
									 text:perfil.etiquetas.lbBtnEliminar
								});
		
		bt_AyudaNomenclador = new Ext.Button({icon:perfil.dirImg+'ayuda.png',iconCls:'btn',id:"Ayuda",
								  handler:Onbt_AyudaNomencladorClick
							   });
							   
		ArbolDPAloader= new Ext.tree.TreeLoader({
							dataUrl:'hijosdpa'
   	 		 });	
   	 	 raizArbolDPA = new Ext.tree.AsyncTreeNode({
	       					text: 'DPA',
							draggable:false,
							id:'iddpa'
   	 		 });	
   	 		 
	 	arbolDPA  = new Ext.tree.TreePanel({
				   		                     id:'ArbolDPA',
							                 region: 'west',
							                 collapsible: true,
							                 title: perfil.etiquetas.lbTitDivPolAdm,
							                 width: 200,
							                 autoScroll: true,
							                 split: true,
							                 minWidth:150,
							                 tbar:[bt_AdicionarNomenclador,bt_ModificarNomenclador,bt_EliminarNomenclador,'-',bt_AyudaNomenclador],
							                 loader: ArbolDPAloader ,
							                 root: raizArbolDPA,
							                 rootVisible: false
							                 //listeners: {click: On_TreeClick}
				            });

   	 	
   	 	arbolDPA.setRootNode(raizArbolDPA);
   	 	arbolDPA.show();
   	 	arbolDPA.expand(true);
   	 	
   	 	
   	 	arbolDPA.on('click',function(nodo,evento){
		    idpadre=nodo.id;
   	       });
   	 		
   	 	
		
		
   	   
   	 	
		function On_RowClick(sm, indiceFila, record){
   					rec = record;
   					SetEstadoBotonesTbarGrid();
   				};

   		function Onbt_AdicionarNomencladorClick(btn){
   			        AddMod = perfil.etiquetas.lbBtnAdicionar;
   					params = null;
	   			    url ='insertar'+referencia;
				    mostrarWin_FormNomenclador();
				};
		
		function Onbt_AyudaNomencladorClick(btn){};
		
   		function Onbt_ModificarNomencladorClick(btn){
   			        AddMod = perfil.etiquetas.lbBtnModificar;
   			 		url ='modificar'+ referencia ; 
   			 		params ="{";
   					params = params + objectid +":"+ DameSeleccionadoNomenclador(objectid) ;
					params = params + "}";
					params = Ext.decode(params);					
	   				mostrarWin_FormNomenclador();
	   				fm_Nomenclador.getForm().loadRecord(rec);
	   				
	   			};
	   	function EliminarRecurrente(){
	   				var cant = sm_MostrarNomenclador.getCount();
	   				console.info(cant)
	   			while(cant>0)
	   				{
	   					//record = st_Nomenclador.getAt(cant-1);
	   					Onbt_EliminarNomencladorClick();
	   					//record = st_Nomenclador.getAt(cant-1);
	   					//console.info(record.data.denom);
	   					//st_Nomenclador.remove(record);	   					
	   					cant = cant-1;
	   				}
	   				
	   	}
	   	function Onbt_EliminarNomencladorClick(){	   					
						if(TieneNomencladorSeleccionados()){
							//mostrarMensaje(1,perfil.etiquetas.lbMsgDebeseleccionarelquedeseaeliminar);
						//else
							mostrarMensaje(2,'¿'+perfil.etiquetas.lbMsgseguroquedeseaeliminar + ' '+ DameSeleccionadoNomenclador("denom")+'?',elimina)
						}
						function elimina(btnPresionado){
							    var cant=sm_MostrarNomenclador.getCount();
								if (btnPresionado =='ok'){										
									params ="{";						   			 		
				   					params = params + objectid +":"+ DameSeleccionadoNomenclador(objectid) ;
									params = params + "}";
									params = Ext.decode(params);
									
										Ext.Ajax.request({
												url: 'eliminar'+referencia,
												method:'POST',
												params:params,
												callback: function (options,success,response){
																	responseData = Ext.decode(response.responseText);
																	mostrarMensaje(responseData.codMsg,responseData.mensaje);
																	st_Nomenclador.reload();
																	sm_MostrarNomenclador.clearSelections();
																	SetEstadoBotonesTbarGrid();
																	
												}
										
										});
										
									
								}
				
						}
				};
    
	    function OnAceptarClick(btn){
	    				sbmt_EnviarfmNomenclador();
	    		};
   	 		
		/*function CrearForma(){
				
					return fm_Nomenclador; 
				};
   	*/
   		function CrearStore(){	
					st_Nomenclador =  new Ext.data.Store({
											url:'mostrar'+referencia,
											listeners:{datachanged:function(){marca()}},
											reader:new Ext.data.JsonReader({
												root:'datos',
												id:objectid,
												totalProperty:'cant'
												},rc_Nomenclador)
						});
			   };
	
		function CrearGrid(nodo){
		bt_AdicionarNomenclador = new Ext.Button({icon:perfil.dirImg+'adicionar.png',iconCls:'btn',id:"Adicionarpsto",												 
									  handler:Onbt_AdicionarNomencladorClick,
									   text:perfil.etiquetas.lbBtnAdicionar
								});
		bt_ModificarNomenclador = new Ext.Button({icon:perfil.dirImg+'modificar.png',iconCls:'btn',id:"Modificarpsto",disabled:true,										 
									  handler:Onbt_ModificarNomencladorClick,
									  text:perfil.etiquetas.lbBtnModificar
								});
		bt_EliminarNomenclador = new Ext.Button({icon:perfil.dirImg+'eliminar.png',iconCls:'btn',id:"Eliminarpsto",disabled:true,												 
									 handler:Onbt_EliminarNomencladorClick,
									 text:perfil.etiquetas.lbBtnEliminar
								});
		
		bt_AyudaNomenclador = new Ext.Button({icon:perfil.dirImg+'ayuda.png',iconCls:'btn',id:"Ayuda",
								  handler:Onbt_AyudaNomencladorClick,text:'Ayuda'
							   });
		if(activo){
		panel1.remove(arbolDPA);
		}					

		gd_Nomenclador = new Ext.grid.GridPanel({
										frame:true,
										//id:'gd_Nomenclador',
										title: nodo.text,
										//iconCls:'icon-grid',
										autoExpandColumn:'expandir',
										store:st_Nomenclador,
										sm:sm_MostrarNomenclador,
										//viewConfig :{forceFit :true},
										loadMask:{msg :perfil.etiquetas.lbMsgCargando},
										columns: cm_Nomenclador,
										keys : [{key:[46],fn:function(){ Onbt_EliminarNomencladorClick();}}],
										tbar:[bt_AdicionarNomenclador,bt_ModificarNomenclador,bt_EliminarNomenclador,'-',new Ext.Toolbar.TextItem({
		text : 'Denominación: '
	}), tf_den = new Ext.form.TextField({
		width : 80,
		id : 'nombre',
		listeners:{
			focus:function(){
				sm_MostrarNomenclador.clearSelections();
			}
		}
	}), new Ext.menu.Separator(), new Ext.Button({
		icon : perfil.dirImg + 'buscar.png',
		iconCls : 'btn',
		text : 'Buscar',
		handler : function() {
			buscarDenom(tf_den.getValue());
		}
	}),"->"/*,bt_AyudaNomenclador*/],
										bbar: new Ext.PagingToolbar({
														pageSize: 20,
														store: st_Nomenclador,
														displayInfo: true,
														displayMsg: perfil.etiquetas.lbMsgResultados,
														emptyMsg: perfil.etiquetas.lbMsgNohayresultadosparamostrar
											})
											
									});

	}
	   function buscarDenom(valor)
	   {
	      st_Nomenclador.load({
			params : {
				den : valor,
				start : 0,
				limit : 20
			}
		});
	   }   
		function sbmt_EnviarfmNomenclador(){
				
				    var fechaini=null;
					var fechafin=null;
										
					if(Ext.getCmp('idfini').getValue()!="") 
					  fechaini = Ext.getCmp('idfini').getValue().format('Ymd');
										
					if(Ext.getCmp('idffin').getValue()!="")   
					   fechafin =  Ext.getCmp('idffin').getValue().format('Ymd');
				                        
					if((fechafin!=null && fechafin < fechaini) )
						{
						mostrarMensaje('3',perfil.etiquetas.lbMsgefechainimayorfechafin);
						return;
						}
						else						
						
					if (fm_Nomenclador.getForm().isValid())
						{
							fm_Nomenclador.getForm().submit(
								{
									url:url,
									params:params,	
									failure: function(form, action){
											mostrarMensaje(action.result.codMsg,action.result.mensaje);
											if(action.result.codMsg != 3 && !activo)
												{
													st_Nomenclador.reload();
														fm_Nomenclador.getForm().reset();
															wn_FormNomenclador.destroy();
											    }
									}
								,waitMsg:perfil.etiquetas.lbMsgEnviandolosdatos})
							
						}												
						else
						
					Ext.Msg.show({
						title:'Error',
						msg: perfil.etiquetas.lbMsgPorfavorverifiquecamposincorrectos,
						buttons: (Ext.Msg.OK ),                   
						animEl: document.body,                   
						icon: Ext.MessageBox.ERROR})
						
					
						
				};
	
		function DameSeleccionadoNomenclador(id){
				var params ="";
					if(TieneNomencladorSeleccionados())
						{
							params = sm_MostrarNomenclador.getSelected().get(id);
						}
						//alert(params);
					return params;
				}
   	
   		function TieneNomencladorSeleccionados(){
   				return (sm_MostrarNomenclador.hasSelection());
   			};

   		function SetEstadoBotonesTbarGrid(){
			if(TieneNomencladorSeleccionados()){
					bt_ModificarNomenclador.enable();
					bt_EliminarNomenclador.enable();
				}
			else{
					bt_ModificarNomenclador.disable();
					bt_EliminarNomenclador.disable();
				}
		}
	
		mostrarWin_FormNomenclador = function (){
			if (objectid=='idgradomilit')
				object.cargarStoreAnterior();
			          if(activo){
				          Ext.Ajax.request({
	   						 url: 'mostrartipodpa',
	   						 params:"idpadre="+idpadre
				    
				         })
			          }
			          
						fm_Nomenclador = new Ext.FormPanel({
									 labelAlign: 'top',
									 frame:true,
									 autoHeight:true,
									 border:'false',
									  bodyStyle:'padding:5px 5px 0',
									 items:[{layout:'column',items: item }]
									 
							});
						wn_FormNomenclador = new Ext.Window({
											title: AddMod+ ' '+nombre,
											layout:'fit',
											width:500,
											autoHeight:true,
											bodyStyle:'padding:5px;',
											items:fm_Nomenclador,
											modal:true,
											buttons:											
											[{
												text:perfil.etiquetas.lbBtnCancelar,
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												handler:function(){
															fm_Nomenclador.getForm().reset();
															wn_FormNomenclador.destroy();
												}
											},{
												text:perfil.etiquetas.lbBtnAceptar,
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												/*handler:function(){
												adicionarDatos();
												}*/
												handler:function(){
                                                OnAceptarClick();
													                }
										  }]
								}
							);
					
								
		wn_FormNomenclador.show(this);
}
function marca() {
				
		if (st_Nomenclador.getCount()!=0)
			{				
				sm_MostrarNomenclador.selectFirstRow();
			}
	}

}


