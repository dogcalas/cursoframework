var perfil = window.parent.UCID.portal.perfil;
// 2.
//UCID.portal.cargarEtiquetas('gestionarnivel', perfil.idioma, function(){cargarInterfaz();});
// 3. Inicializo el singlenton QuickTips
Ext.QuickTips.init();
/*
 *   @include "/views/js/eav/AdminCombo.js" @include "/views/js/estructura/gestionarestructura.js"
 */
Ext.MessageBox.buttonText.yes = "Si";
Ext.MessageBox.buttonText.ok = "Aceptar";

function cargarInterfaz()
	{
		Ext.onReady(function(){	
			/**
 			* @include "/metadatos/views/js/eav/TreeGrid.js"
 			*/
			/** VARIABLE QUE INDICA QIE OPCION SE ESCOGIO
			  *(MODIFICAR O ELIMINAR) EN GRID-PANEL CAMPO*/
			var bandera; 	
			
			/** VARIABLE QUE INDICA QIE OPCION SE ESCOGIO
			  *(MODIFICAR O ADICIONAR) EN EL TREE-GRIDPANEL TABLAS */			
			var banderaT; 
			
			var id_TipoEst; /** */
			
			var vGestionTablas; /** */
			
			var vGestionCampos;  /** */
			
			var vGestionFPCampos;
			
			
			//botones
			btnModificarCamp=new Ext.Button({
									icon:perfil.dirImg+'modificar.png',
									iconCls:'btn',
									disabled:true,
									id:"Modificar",
									handler:function(){
											bandera = "Modificar";
											ModificarCampos();},
									text:'Modificar'
								})
		    
			btnEliminarCamp=new Ext.Button({	
									icon:perfil.dirImg+'eliminar.png',
									iconCls:'btn',
									disabled:true,
									id:"Eliminar",
									handler:function(){eliminarCampo();},
									text:'Eliminar'
								});
			var btn_EditarCombo =new Ext.Button({
								id:'btnEditcbb',
								iconCls:'btn',
								icon:perfil.dirImg+'modificar.png',
								text:'Editar lista de valores',
								disabled:true,
								handler:function(){ GestionarCombo();}			
						});
			
			// buscar los tipos de datos a trabajar
			/*
			 * Stores
			 */
			var aleatorio = getRandomNum(1,100000000);
			
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

			var SelecEst=[['op', 'Internas'],['', 'Externas']];
			
			var stVisible= new Ext.data.SimpleStore({
        						fields: ['valor', 'bol'],
        						data : Datos
    						});
    		var stTipoEstructura = new Ext.data.SimpleStore({
        						fields: ['id_est', 'est'],
        						data : SelecEst
    						});
			
			var stIcono=new Ext.data.Store({
			autoload: true,
            proxy: new Ext.data.HttpProxy({url: 'mostrariconos'}),
            reader: new Ext.data.JsonReader({
            totalProperty: "cant",
            root: "datos",
            id: "id"    
            }, [{name: 'id',mapping: 'id'},
			    {name: 'valor',mapping: 'valor'},
				{name: 'icono',mapping: 'icono'}				
				])
			});				
			stIcono.load();
			var stCampos= new Ext.data.Store({
    							autoLoad: true,
    							proxy: new Ext.data.HttpProxy({ url: 'mostrartiposcampos'}),
    							reader: new Ext.data.JsonReader({
       										totalProperty: "cant",
        									root: "datos",
        									id: "id"    
    										},[{
        										name: 'id',mapping: 'id'
        						 			},{
        						 				name: 'tipo',mapping: 'tipo'
        						 			}] 
								)
							});	


			
			//Formulario adicionar tablas
			var chk_boxRecursiva = new Ext.form.Checkbox({										
										fieldLabel:'Marcar si es recursiva',
										boxLabel:'Recursiva',
										checked :false,
										id:'idrecursiva'
											
			});
			
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
													anchor:'97.5%',
													allowBlank:false,
													blankText:'Este campo es requerido'
													//regex:/^([a-zA-Z]+?[a-zA-Z]*)+[0-9]+$/
												}]
											},{
												columnWidth:.5,
												layout: 'form',
												items: [{
													xtype:'datefield',
													fieldLabel: 'Fecha inicio',
													readOnly:true,
													id: 'fechaini',
													format :'d/m/Y',
													value : new Date(),
													anchor:'95%'
													
												},{
													xtype:'iconcombo',													
													fieldLabel: 'icono',													
													id: 'idicono',
													autoCreate:true,
													store: stIcono,
                                                    valueField: 'id',
                                                    displayField: 'valor',
                                                    iconClsField: 'icono',													
                                                    triggerAction: 'all',													
												    forceSelection:true,
													hideLabel:false,
												    hiddenName:'id',
												    emptyText:'Seleccione el icono..',
													editable:false,
                                                    mode: 'local'													
												}]												
											},{
												columnWidth:.5,
												layout: 'form',
												items: [{
													xtype:'datefield',
													readOnly:true,
													fieldLabel: 'Fecha fin',
													format :'d/m/Y',
													id: 'fechafin',
													anchor:'95%'
												}//,chk_boxRecursiva
												]
											}]
										}]
								});

		//Formulario Adicionar campos
								
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
												regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,
												regexText:'Solo se permiten n&uacute;meros y letras sin espacio',												
												allowBlank:false,
												blankText:'Este campo es requerido',
												anchor:'93%'
											 },{
											 	xtype:'combo',
												fieldLabel: 'Tipo dato',
												store:stTipos,
												id:"tipod",
												valueField:'id',
												editable :false,
												triggerAction:'all',
												forceSelection:true,
												emptyText:'Seleccione el tipo..',
												displayField:'tipo',
												hideLabel:false,
												hiddenName:'tipoDato',
												autoCreate: true,
												mode: 'local',
												forceSelection: true,
												anchor:'93%'
											},{
												xtype:'textfield',
												fieldLabel:'Valor por defecto',
												id: 'vpd',
												//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,
												//regexText:'Solo se permiten numeros y letras sin espacio',												
												allowBlank:true,
												//blankText:'Este campo es requerido',
												anchor:'93%'
											 }]
										},{
											columnWidth:.32,
											layout: 'form',
											items: [{
												xtype:'textfield',
												fieldLabel: 'Alias',
												id: 'nombrec',
												//regex:/^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
												//regexText:'Valor incorrecto',
												allowBlank:false,
												blankText:'Este campo es requerido',
												anchor:'93%'
											},{
												xtype:'combo',
												fieldLabel: 'Tipo campo',					
												store:stCampos,
												id:"tipoc",
												editable :false,
												triggerAction:'all',
												forceSelection:true,
												emptyText:'Seleccione el tipo..',
												displayField:'tipo',
												valueField:'id',
												hiddenName:'tipoCampo',
												hideLabel:false,
												autoCreate: true,
												mode: 'local',
												forceSelection: true,
												anchor:'93%'
											}]
										},{
											columnWidth:.32,
											layout: 'form',
											items: [{
												xtype:'numberfield',
													fieldLabel: 'Longitud',
													id: 'longitud',
													regex:/^\d*$/,
													allowBlank:false,
													blankText:'Este campo es requerido',
												    regexText:'Valor incorrecto',
													anchor:'100%'
											},{
												xtype:'combo',
													fieldLabel: 'Visible',
													store:stVisible,
													hideLabel:false,
													hiddenName:'Visible',
													mode: 'local',
													editable :false,
													triggerAction:'all',
													forceSelection:true,
													emptyText:'Seleccione visibilidad...',
													valueField:'valor',
													displayField:'bol',
													id: 'visible',					
													anchor:'100%'  
											}]
										}]
									},{
												xtype:'textarea',
													height:70,
													width:100,
													fieldLabel: 'Descripci&oacute;n',
													id: 'descripcion',
													anchor:'100%'
											}]
								});

		//--------->Especificacion de la forma de seleccion de las filas del grid
								
			var modoSeleccionTabla = new Ext.grid.RowSelectionModel({
     							singleSelect:true
	 						}); 	 

//--------->Especificacion de la forma de seleccion de las filas del grid
	 						
			var modoSeleccionCampo = new Ext.grid.RowSelectionModel({
     							singleSelect:true
	 						});

 
//Botones del Grid de la gestion de Tablas
			var btnAdicionar=new Ext.Button({
								text:'Adicionar',
								icon:perfil.dirImg+'adicionar.png',
								id:'btnAdicionar',
								iconCls:'btn',
								handler:function(){ banderaT= "Adicionar" ;LimpiaForm_Tablas(); mostrarVGestionTablas();}								
						});
	
			var btnModificar=new Ext.Button({	icon:'../images/icon/modificar.png',
	        					text:'Modificar',
	        					id:'btnModificar',
								icon:perfil.dirImg+'modificar.png',
								disabled:true,
								iconCls:'btn',
								handler:function(){banderaT= "Modificar" ; ModificarEstructuras(); }
								
						})	
	
			var btnEliminar=new Ext.Button({	icon:perfil.dirImg+'eliminar.png',
								id:'btnEliminar',
								icon:perfil.dirImg+'eliminar.png',
								text:'Eliminar',
								disabled:true,
								iconCls:'btn',
								handler:function(){ eliminarTabla();}			
						});

			var btnGestionarcampos=new Ext.Button({	icon:perfil.dirImg+'visualizar.png',
											text:'Gestionar campos',			
											//icon:'btn',
											id:'btnGestionarcampos',
											disabled:true,
											handler:function(){
														stGestionCampos.load(
																{ params:{start:0,limit:20,idtabla:DameSeleccionado("idnomeav"),SUFIX :id_TipoEst}});
														mostrarVGestionCampos();
													}
						});
	
	/** ComboBox para seleccionar el tipo de estructura * 
	 * 
	 */
	 		var cbbox_TipoEstruct = new Ext.form.ComboBox({
										store:stTipoEstructura,
										id:"tipest",
										valueField:'id_est',
										editable :false,
										triggerAction:'all',
										forceSelection:true,
										emptyText:'Seleccione el tipo..',
										displayField:'est',
										hideLabel:false,
										hiddenName:'id',
										disableKeyFilter:true,
										selectOnFocus : true,
										autoCreate: true,
										mode: 'local',
										anchor:'93%'
						});
	
	/**Store del Grid que muestra los tipos de metadatos
	 * 
	 */
   		 	var store01 = new Ext.ux.maximgb.treegrid.AdjacencyListStore({
    							//autoLoad : true,
    							url: 'cargarhijos',
								reader: new Ext.data.JsonReader({
											id: 'idnomeav',
											root: 'datos',
											totalProperty: 'cant',
											successProperty: 'success'
											},
											[{
												name: 'nombre'
											},{
												name: 'fechaini'
											},{
												name: 'fechafin'
											},{
												name: 'idnomeav'
											},{
												name: '_parent'
											},{
												name: '_is_leaf'
											}]			
									)
    					});
	
    /**STORE QUE TRAE LOS CAMPOS DA LAS TABLAS**
     * 
     */    
			var stGestionCampos =  new Ext.data.Store({
										url: 'mostrarcampos',	
										reader:new Ext.data.JsonReader({
											root:'datos',
											id:'nombre',
											totalProperty:'totalRecords'
											},
											[
											{name: 'nombre'},
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
	/**TREE-GRID QUE PERMITE GESTINAR EL CONTENIDO DE LAS TABLAS 
 	 * 	
 	 */
    		var gdGestionTablas = new Ext.ux.maximgb.treegrid.GridPanel({
    							title:'Gestion de tablas',
    							store: store01,
      							master_column_id : 'expandir',
      							autoExpandColumn:'expandir',
      							stripeRows: true,
      							sm:modoSeleccionTabla,
      							loadMask:{msg :'Cargando Estructuras'},
      							root_title: 'Estructuras',
      							columns: [
										  {id:'expandir',header: 'Nombre', dataIndex: 'nombre'},
        								  {header: 'Fecha de inicio', width:100, dataIndex: 'fechaini'},
        								  {header: 'Fecha de fin', width:100, dataIndex: 'fechafin'}
        						],
        						tbar:[
        							btnAdicionar,
        							btnModificar,
        							btnEliminar,
        							btnGestionarcampos,
        							'->',"Seleccione la estructura: ",
        							cbbox_TipoEstruct
        							],
      							bbar: new Ext.ux.maximgb.treegrid.PagingToolbar({
      										store: store01,
      										displayInfo: true,
      										displayMsg: 'Resultados de {0} - {1} de {2}',
      										emptyMsg: "No hay resultados para mostrar.",
      										pageSize: 10
      							})
    						});



	/**Grid Gestion de Campos
	 * 
	 */
			var gdGestionCampos = new Ext.grid.GridPanel({
							frame:true,
							iconCls:'icon-grid',
							autoExpandColumn:'expandir',
							store:stGestionCampos,
							loadMask:{msg :'Cargando campos'},
							sm:modoSeleccionCampo,
							columns: [
										{id:'expandir',header: 'Nombre', dataIndex: 'nombre'},
										{header: 'Alias', width:80, dataIndex: 'nombre_mostrar'},
										{header: 'Longitud', width:55, dataIndex: 'longitud'},
										{header: 'Tipo dato', width:60, dataIndex: 'tipo'},
										{header: 'Tipo campo', width:70, dataIndex: 'tipocampo'},
										{header: 'Visible?', width:55, dataIndex: 'visible'},
										{header: 'Reg exp', width:55, dataIndex: 'regex'},
										{header: 'Descripcion', width:100, dataIndex: 'descripcion'}
							],
							tbar:[
									btnAdicionarCamp=new Ext.Button({
									icon:perfil.dirImg+'adicionar.png',
									iconCls:'btn',
									id:"Adicionar",
									handler:function(){
											bandera = "Adicionar";
											LimpiaForm();
											mostrarVGestionFPCampos();},
									text:'Adicionar'})
								,btnModificarCamp,btnEliminarCamp,'-',btn_EditarCombo,{	
									icon:perfil.dirImg+'ayuda.png',
									iconCls:'btn',
									id:"Ayuda",
									handler:function(){}
								}],
								bbar: new Ext.PagingToolbar({
											pageSize: 5,
											store: stGestionCampos,
											displayInfo: true,
											displayMsg: 'Resultados de {0} - {1} de {2}',
											emptyMsg: "No hay resultados para mostrar."
								})
						});
						
			//var gest_Recursividad = new GestionarRecursiva();			
	/** VIEWPORT QUE MOSTRATA EL TREE-GRID
 	*/
						
			var vpGestionTablas = new Ext.Viewport({
									layout:'fit',
									items:gdGestionTablas
							});

/***-----------------FUNCIONES-----------------------------------*/
	
	/** Eventos de los Componetes del formulario*
 	 */
			modoSeleccionCampo.on("selectionchange", function(_sm, indiceFila, record){
  												if(_sm.getSelections().length>0){
												
												}  
  				}); 
				
				modoSeleccionCampo.on("rowselect", function(_sm, indiceFila, record){
												EditCombo.idcombo = record.data.idcampo;
  												Chk_BotonGestionarCampos();
  				}); 
				
  				
		    		
	/**--------->EVENTO PARA ACTIVAR LOS BOTONES----------------------*/
  			var EditCombo = new AdminCombo();	
			fpGestionCampos.findById('tipoc').on("valid",function(c){
														 value = c.getValue()
														 DesabilitarValor();																  
											});
		chk_boxRecursiva.on("check",function(comp,bol){
							//if(bol)gest_Recursividad.mostrarWin_GestRecursiva();
											});								
			modoSeleccionTabla.on("rowselect",function(_sm, indiceFila, record){	
   					btnAdicionar.enable();
   					Chk_BotonGestionarEAV();
   					rec = record;
   				});
   				
	   		cbbox_TipoEstruct.on('select',function(c,r,i){
	   				id_TipoEst =r.get("id_est");
	   				//alert(id_TipoEst);
	   				modoSeleccionTabla.clearSelections();
	   				store01.baseParams ={SUFIX:id_TipoEst};
	   				store01.load({params:{start:0, limit:10}});
	   				
	   				})
	   				
			function LimpiaForm(){
					fpGestionCampos.findById('nombre').setValue("");
					fpGestionCampos.findById('nombrec').setValue("");
					fpGestionCampos.findById('longitud').setValue("");
					fpGestionCampos.findById('tipod').setValue("");
					fpGestionCampos.findById('nombrec').setValue("");
					fpGestionCampos.findById('tipoc').setValue("");
					fpGestionCampos.findById('visible').setValue("");
					fpGestionCampos.findById('descripcion').setValue("");
				}
			function LimpiaForm_Tablas(){
					fpGestionTablas.findById('nombre').setValue("");
					fpGestionTablas.findById('fechaini').setValue( new Date() );
					fpGestionTablas.findById('fechafin').setValue("");
				}				
				
			DameSeleccionado=function (id){
					var params ="";
					if(modoSeleccionTabla.hasSelection()) {
							params = modoSeleccionTabla.getSelected().get(id);
						}
					return params;
				}
				
			function DameSeleccionadoCampos(id){
					var params ="";
					if(modoSeleccionCampo.hasSelection())
						{
							params = modoSeleccionCampo.getSelected().get(id);
						}
					return params;
				}
	
			function cargarValores(){
					fpGestionTablas.findById('nombre').setValue(DameSeleccionado("nombre"));
					fpGestionTablas.findById('fechaini').setValue(DameSeleccionado("fechaini"));
					fpGestionTablas.findById('fechafin').setValue(DameSeleccionado("fechafin"))
				}
	/**Carga los valores del Grid Gestion de campos en su 
	 * respectivos formularios
	 */
			function cargarValoresCampos(){
					fpGestionCampos.findById('nombre').setValue(DameSeleccionadoCampos("nombre"));
					fpGestionCampos.findById('nombrec').setValue(DameSeleccionadoCampos("nombre_mostrar"));
					fpGestionCampos.findById('longitud').setValue(DameSeleccionadoCampos("longitud"));
					fpGestionCampos.findById('tipod').setValue(DameSeleccionadoCampos("tipo"));
					fpGestionCampos.findById('nombrec').setValue(DameSeleccionadoCampos("nombre_mostrar"));
					fpGestionCampos.findById('tipoc').setValue(DameSeleccionadoCampos("tipocampo"));
					fpGestionCampos.findById('visible').setValue(DameSeleccionadoCampos("visible"));
					fpGestionCampos.findById('descripcion').setValue(DameSeleccionadoCampos("descripcion"));
				}

		
	/**FUNCION QUE GESTIONA QUE FUNCION EJECUTARA
	 * LA VENTANA AL PULSAR ACEPTAR(ADICIONAR O MODIFICAR)
	 * PARA EL FORMULARIO GESTIONAR CAMPO
	 * @return {AdminCombo}
	 */
			function GestionarFuncion(){									
					if(bandera =="Modificar"){
							EnviarForm('modificarcampos',{idcampo:DameSeleccionadoCampos("idcampo"),SUFIX:id_TipoEst});
						}
					if(bandera =="Adicionar"){
							EnviarForm('insertacampos',{idtabla:DameSeleccionado("idnomeav"),SUFIX:id_TipoEst});
					}
				} 
	/**FUNCION QUE GESTIONA QUE FUNCION EJECUTARA
	 * LA VENTANA AL PULSAR ACEPTAR(ADICIONAR O MODIFICAR)
	 * PARA EL FORMULARIO GESTIONAR TABLA
	 */
			function GestionarFuncionTabla(){					
					if(banderaT =="Modificar"){
						
							EnviarEAV('modificartablas',{idtabla:DameSeleccionado("idnomeav"),SUFIX:id_TipoEst });
						}
					if(banderaT =="Adicionar"){
							EnviarEAV('insertatablas',{idpadre:DameSeleccionado("idnomeav"),SUFIX:id_TipoEst});
					}
				}
	/**
	 * Funcion para adicionar  CAMPOS AL GRID
	 * 
	 */
			function EnviarForm(url,params){
					if (fpGestionCampos.getForm().isValid())
						{	
							fpGestionCampos.getForm().submit({
									url:url,
									params:params,
									waitTitle:'Aviso',
									waitMsg:'Enviando datos...',			
									failure: function(form, action){
											mostrarMensaje(action.result.codMsg,action.result.mensaje);
											if(action.result.codMsg != 3){ vGestionFPCampos.hide(); }
											modoSeleccionCampo.clearSelections();
											Chk_BotonGestionarCampos();
											stGestionCampos.reload();
									}
								});
						}
				}
	/**Funcion para adicionar estructuras al TREE-GRID
	 * 
	 */
	
			function EnviarEAV(url,params)
					{
						if (fpGestionTablas.getForm().isValid())
							{
								fpGestionTablas.getForm().submit({
										url:url,
										params:params,
										waitTitle:'Aviso',
										waitMsg:'Enviando datos...',
										success:function(form, action){
												store01.load();
												mostrarMensaje(action.result.codMsg,action.result.mensaje);
										 },
										failure: function(form, action){
												modoSeleccionTabla.clearSelections();
												store01.load();
												mostrarMensaje(action.result.codMsg,action.result.mensaje);
												if(action.result.codMsg != 3){											
														fpGestionTablas.getForm().reset();
														vGestionTablas.hide();
												 }
										 }
									});
							}
							
							modoSeleccionTabla.clearSelections();
							Chk_BotonGestionarEAV()
					};
		function Chk_BotonGestionarEAV()
			{
		 	if(modoSeleccionTabla.hasSelection()){
		 		btnModificar.enable();
   				btnEliminar.enable();
   				btnGestionarcampos.enable();
		 	}
		 	else{
		 		btnModificar.disable();
   				btnEliminar.disable();
   				btnGestionarcampos.disable();
			}
		}	
		
		function Chk_BotonGestionarCampos()
			{
		 	if(modoSeleccionCampo.hasSelection()){
		 		btnModificarCamp.enable();
   				btnEliminarCamp.enable();
   				if(modoSeleccionCampo.getSelected().data.tipocampo == 'combo')
   						btn_EditarCombo.enable();
   				else {btn_EditarCombo.disable()}
		 	}
		 	else{
		 		btnModificarCamp.disable();
   				btnEliminarCamp.disable();
   				btn_EditarCombo.disable();
			}
		}
	/**
	 * FUNCION PARA MODIFICAR LOS DATOS DEL FORMULARIO DE GESTION
	 * DE LAS ESTRUCTURAS
	 */
			function ModificarEstructuras(){						
						cargarValores();
						mostrarVGestionTablas();
				}
	/**
	 *FUNCION PARA MODIFICAR LOS DATOS DEL FORMULARIO DE GESTION
	 * DE LOS CAMPOS 
	 */
			function ModificarCampos(){
						cargarValoresCampos();
						mostrarVGestionFPCampos();
				}
	/**
	 * Ventana Formulatio de Gestion de Tablas
	 */
			function eliminarTabla(){
					if(!modoSeleccionTabla.getSelected())
						mostrarMensaje(1,'Debe seleccionar el Nivel 1 que desea eliminar');
					else{
						mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar al nivel '+modoSeleccionTabla.getSelected().get('nombre')+' ?',elimina)
					}
					function elimina(btnPresionado){
							if (btnPresionado == 'ok'){
									Ext.Ajax.request({
											url: 'eliminartabla',
											method:'POST',
											params:{idtabla:modoSeleccionTabla.getSelected().data.idnomeav,SUFIX :id_TipoEst},
											callback: function (options,success,response){
																responseData = Ext.decode(response.responseText);
																mostrarMensaje(responseData.codMsg,responseData.mensaje);
																modoSeleccionTabla.clearSelections();
																Chk_BotonGestionarEAV();
																store01.load();
											}
									});
							}
			
					}
					
				}	

			function eliminarCampo(){
						if(!modoSeleccionCampo.getSelected())
								mostrarMensaje(1,'Debe seleccionar el campo que desea eliminar');
						else{
								mostrarMensaje(2,'&iquest;Est&aacute; seguro que desea eliminar el campo'+modoSeleccionCampo.getSelected().get('nombre')+'?',elimina)
						}
						function elimina(btnPresionado){
								if (btnPresionado == 'ok'){
								Ext.Ajax.request({
											url: 'eliminarcampos',
											params:{idcampo:modoSeleccionCampo.getSelected().get('idcampo'),SUFIX :id_TipoEst},
											callback: function (options,success,response){
															responseData = Ext.decode(response.responseText);
															mostrarMensaje(responseData.codMsg,responseData.mensaje);
															modoSeleccionCampo.clearSelections();
															Chk_BotonGestionarCampos();
															stGestionCampos.reload();
											}
								});
						}
					}
				}
		
	/**
	 * VENTANA QUE CONTIENE EL FORMULARIO
	 * PARA GESTIONAR LA TABLA
	 */

			function mostrarVGestionTablas(){
						if(!vGestionTablas){
								vGestionTablas = new Ext.Window({
												title:'Gestion de tablas',
												layout:'fit',
												width:450,
												height:300,
												modal:true,
												items:fpGestionTablas,							
												closeAction:'hide',
												buttons:
												[{
													text:'Cancelar',
													icon:'../images/icon/cancelar.png',
													iconCls:'btn',
													handler:function(){
																fpGestionTablas.getForm().reset();
																vGestionTablas.hide();
													}
												},{
													text:'Aceptar',
													icon:'../images/icon/aceptar.png',
													iconCls:'btn',
													handler:function(){GestionarFuncionTabla();}						
												}]
									});
						}
						vGestionTablas.show(this);
					}

	/**Ventana grid Gestion de Campos
	 * 
	 */
			function mostrarVGestionCampos(){
			Chk_BotonGestionarCampos();
					if(!vGestionCampos){						
							vGestionCampos = new Ext.Window({
												title:'Gestion de Campos',
												layout:'fit',
												width:600,
												modal:true,
												height:300,
												closeAction:'hide',
												items:gdGestionCampos
											});
					
					}
				    vGestionCampos.show(this);
				}
	
	/** Ventana Formulario gestion de campos
	 *
	 */
			function mostrarVGestionFPCampos(){
					if(!vGestionFPCampos){
							vGestionFPCampos = new Ext.Window({
													title:'Campo',
													layout:'fit',
													width:450,
													modal:true,
													items:fpGestionCampos,
													height:330,
													closeAction:'hide',
													buttons:
														[{
															text:'Cancelar',
															icon:'../images/icon/cancelar.png',
															iconCls:'btn',						
															handler:function(){ LimpiaForm(); vGestionFPCampos.hide(); }
														},{
															text:'Aceptar',
															icon:'../images/icon/aceptar.png',
															iconCls:'btn',
															handler:function(){GestionarFuncion();}
														}]
							});
					}
					vGestionFPCampos.setTitle(bandera + " Campo");
					vGestionFPCampos.show(this);
				}
			
			GestionarCombo = function(){
					EditCombo.idcombo = DameSeleccionadoCampos("idcampo");
					EditCombo.mostrarWin_AdminCombo();					
				}
			DesabilitarValor = function(){
				 	if(value =="combo"){
			         	fpGestionCampos.findById('vpd').disable();	
				 	}
				 	else {
				 		fpGestionCampos.findById('vpd').enable();	
				 	}

			}
			 
	
	/**Ventana auxiliar de mensajes
	 * 
	 */ 

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
		
			function getRandomNum(lbound, ubound){
	                return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
				}
	
		
		});
	}
cargarInterfaz()