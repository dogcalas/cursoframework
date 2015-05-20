
Ext.namespace('Erp.ec');
Erp.ec.GestionarVPD = function(config) {
	this.st;
	this.Cargar = function(){return this.st;};
	Ext.applyIf(this, config);
	this.initUIComponents();
	Erp.ec.GestionarVPD.superclass.constructor.call(this);
};
Ext.extend(Erp.ec.GestionarVPD, Ext.Window, {
	initUIComponents : function() {
		// BEGIN OF CODE GENERATION PARTS, DON'T DELETE CODE BELOW
		var rec;
		var record = new Ext.data.Record.create([{
										name : 'idvalordefecto'
								  },{
								  		name : 'valor'
								  }]);	
								  
		var store1 = new Ext.data.Store({
							url:"mostrarvalorcombo",
							reader:new Ext.data.JsonReader({
								root:'datos',
								id:'idvalor',
								totalProperty:'cant'											
								},record)
						});
		this.st = store1;
					
		var selmodel1= new Ext.grid.RowSelectionModel({singleSelect:true,listeners:{rowselect:OnSelectRowClick}});
		
		var btn_Eliminar = new Ext.Button({
							text:"Eliminar",
							iconCls:"btn",
							disabled:true,
							id:'btnEliminar',
							icon:perfil.dirImg+'eliminar.png',
							handler:OnEliminarClick,
							tooltip:"Eliminar valor por defecto"
				});
		this.tbar = new Ext.Toolbar([{
							text:"Adicionar",
							iconCls:"btn",
							id:'btnAdicionar',												
							icon:perfil.dirImg+'adicionar.png',
							handler:OnAdicionarClick,
							tooltip:"Adicionar valor por defecto"
						},btn_Eliminar]);
		
		var text = new Ext.form.TextField({
							name:'text',
							id:'text',
							allowBlank: false
							//maxLength: this.longitud,
							//regex:this.regex
				});
		var idVpd = new Ext.form.TextField({
							name:'vpd',
							id:'idVpd',
							allowBlank: false,
							width:50
							//maxLength: this.longitud,
							//regex:this.regex
				});

		this.editorGridPanel1 = new Ext.grid.EditorGridPanel({
									store:store1,
									loadMask:{msg :'Cargando Valores ....'},
									columns:[{dataIndex:"valor",header:"Nombre",sortable:true,editor: text}/*,
											 {dataIndex:"valorid",header:"Id",sortable:true,editor: idVpd,width:50}*/],
									sm:selmodel1,
									tbar:this.tbar
			});


		Ext.apply(this,{height:300,
						items:[this.editorGridPanel1],
						layout:"fit",
						width:200,
						xtype:"window",
						title:'Definir valores por defecto',
						closeAction:'hide'
		});
		
		function OnAdicionarClick(){
				var vacia = new record({
						idvalordefecto : '',
				  		valor : '',
				  		valorid : ''
				  	});
				//this.editorGridPanel1.stopEditing();
                store1.insert(0,vacia);
              //  this.editorGridPanel1.startEditing(0, 0);
				
		};
		
		function OnEliminarClick(){
			store1.remove(rec)
			//this.selmodel1.clearSelections();
			//this.editorGridPanel1.getSelectionModel().clearSelections();
			//SetEstadoBotonesTbarGrid();
		};
		
		function OnSelectRowClick(sm, indiceFila, record){
   					rec = record;
   					SetEstadoBotonesTbarGrid(sm);
   				};
   		function SetEstadoBotonesTbarGrid(sm){
   					if(sm.hasSelection())
							btn_Eliminar.enable();
					else
							btn_Eliminar.disable();
   				};
// END OF CODE GENERATION PARTS, DON'T DELETE CODE ABOVE
	}
});

/** *********************************caso de gestion 2 **************************/
var idtabla;
Erp.ec.GridGestionarCampos = function(config) {
	this.store;
	
	this.SetidTabla = function(idt){ idtabla = idt;}
	this.getStore = function(){return this.store;};
	
	Ext.applyIf(this, config);
	this.initUIComponents();
	Erp.ec.GridGestionarCampos.superclass.constructor.call(this);
};

Ext.extend(Erp.ec.GridGestionarCampos, Ext.Panel, {
			initUIComponents : function() {
				// BEGIN OF CODE GENERATION PARTS, DON'T DELETE CODE BELOW
				var params,url,wnFormCampos,banderavpd,
					rec,addormodify;
				var referencia = 'campos';
				var win = new Erp.ec.GestionarVPD();
				var idcampo;
				var idbase;
				var ArrayVpd = [];
				
				var store1 = new Ext.data.Store({
									url:"mostrar"+referencia,
									listeners:{load  :onGridLoad },
									reader:new Ext.data.JsonReader({totalProperty:"cant", //totalRecords
			  										id:"idcampo",
													root:"datos"
											   },[{
											   		name:"nombre"
											   	 },{
											   	 	name:"nombre_mostrar"
											   	 },{
											   	 	name:"longitud"
											   	 },{
											   	 	name:"tipo"
											   	 },{
											   	 	name:"tipocampo"
											   	 },{
											   	 	name:"visible"//,type:'bool'
											   	 },{
											   	 	name:"descripcion"
											   	 },{
											   	 	name:"idcampo"
											   	 }])
						});
				//this.store1.baseParams = {idtabla:this.base};
				
				this.store = store1;
				
				this.columns = [{
									width:100,	sortable:true,dataIndex:"nombre",header:"Nombre"
								},{
									width:75,sortable:true,dataIndex:"nombre_mostrar",header:"Alias"
								},{
									width:50,sortable:true,	dataIndex:"longitud",header:"Longitud"
								},{
									width:75,sortable:true,dataIndex:"tipo",header:"Tipo dato"
								},{
									width:75,sortable:true,dataIndex:"tipocampo",header:"Tipo campo"
								},{
									width:50,sortable:true,	dataIndex:"visible",header:"Visible"
								},{
									width:100,sortable:true,dataIndex:"descripcion",header:"Descripción"
								}/*,{
									width:120,sortable:true,dataIndex:"valor",header:"Valor (es) por defecto"
								}*/];		
				this.tbar = new Ext.Toolbar([{
												text:"Adicionar",
												iconCls:"btn",
												id:'btnAdicionar',											
												icon:perfil.dirImg+'adicionar.png',
												handler:OnAdicionarClick,
												tooltip:"Adicionar campo"
											},{
												text:"Modificar",
												iconCls:"btn",
												id:'btnModificar',
												disabled:true,
												icon:perfil.dirImg+'modificar.png',
												handler:OnModificarClick,
												tooltip:"Modificar campo"
											},{
												text:"Eliminar",
												iconCls:"btn",
												disabled:true,
												id:'btnEliminar',
												icon:perfil.dirImg+'eliminar.png',
												handler:OnEliminarClick,
												tooltip:"Eliminar campo"
									}]);
				var selmodel = new Ext.grid.RowSelectionModel({singleSelect:true,listeners: {rowselect:OnSelectRowClick}});
				
				this.girdcampos = new Ext.grid.GridPanel({
									tbar:this.tbar,
									store:store1,
									columns:this.columns,
									sm:selmodel,									
									bbar:new Ext.PagingToolbar({
											store:store1,
											displayInfo: true,
											displayMsg: 'Resultados de {0} - {1} de {2}',
      										emptyMsg: "No hay resultados para mostrar.",
      										pageSize: 10,
											xtype:"paging"
								})
						});
				var Datos=[['1', 'Si'],['0', 'No']];

				var stVisible= new Ext.data.SimpleStore({
        						fields: ['visible', 'bol'],
        						data : Datos
    						});

   				 var stCampos= new Ext.data.Store({
    							autoLoad: true,
    							proxy: new Ext.data.HttpProxy({ url: 'mostrartiposcampos'}),
    							reader: new Ext.data.JsonReader({
       										totalProperty: "cant",
        									root: "datos",
        									id: "tipo"
    										},[{
        						 				name: 'tipocampo',mapping: 'tipo'
        						 			}]
								)
							});
							
				var stTipos= new Ext.data.Store({
							autoLoad: true,
    						proxy: new Ext.data.HttpProxy({	url: 'mostrartiposdatos'}),
    			 			reader: new Ext.data.JsonReader({
        								totalProperty: "cant",
        								root: "datos",
        								id: "tipo"
        								},[{
        								 	name: 'tipo',
        								 	mapping: 'tipo'
        								 },{
        								 	name: 'regex',
        								 	mapping:'regex'
        								 }]
        					)
        				});
/**************************COLUMNA 1 **********************************************/
        		var Nombre = new Ext.form.TextField({
								fieldLabel: 'Nombre',
								name:"nombre",
								id:"idnombre",
								regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,
								regexText:'Sólo se permiten números y letras sin espacio',												
								allowBlank:false,
								tabIndex: 1,								
								anchor:'93%',
								listeners:{change:OnNombreChange}
							});
							
				var TipoDato  = new Ext.form.ComboBox({
							 	xtype:'combo',
							 	id:"idtipodato",
								fieldLabel: 'Tipo dato',
								store:stTipos,
								editable :false,
								triggerAction:'all',
								forceSelection:true,
								emptyText:'Seleccione el tipo..',
								hideLabel:false,
								autoCreate: true,
								mode: 'local',
								allowBlank:false,
								forceSelection: true,
								anchor:'93%',
								listeners:{select:onTipoDatosSelect},
								valueField:'tipo',
								hiddenName:'tipo',
								displayField:'tipo'
							});
				var vpd = new Ext.form.TextField({
								xtype:'textfield',
								fieldLabel:'Valor por defecto',
								name: 'vpd',
								id: 'vpd',
								allowBlank:true,
								disabled:false,
								anchor:'93%',
								listeners:{focus:onValorDefectoFocus}
							 });
							 
				var cf_1 = {columnWidth:.32,layout: 'form',items: [Nombre,TipoDato,vpd]}
/**************************COLUMNA 2 **********************************************/				
				var Alias = new Ext.form.TextField({
								xtype:'textfield',
								fieldLabel: 'Alias',
								id:"idAlias",
								name:"nombre_mostrar",
								allowBlank:false,
								blankText:'Este campo es requerido',
								anchor:'93%'
							});
				var TipoCampo  = new Ext.form.ComboBox({
								xtype:'combo',
								fieldLabel: 'Tipo campo',
								id:"idTipoCampo",
								store:stCampos,
								editable :false,
								triggerAction:'all',
								forceSelection:true,
								emptyText:'Seleccione el tipo..',								
								hideLabel:false,
								autoCreate: true,
								allowBlank:false,
								mode: 'local',
								forceSelection: true,
								anchor:'93%',
								listeners:{select:onTipoCamposSelect},
								displayField:'tipocampo',
								valueField:'tipocampo',
								hiddenName:'tipocampo'
							});
							
				var cf_2 = {columnWidth:.32,layout: 'form',	items: [Alias,TipoCampo]};

/**************************COLUMNA 3 **********************************************/				
				var Longitud = new Ext.form.TextField({
									xtype:'numberfield',
									fieldLabel: 'Longitud',
									id:"idLongitud",
									name:"longitud",
									regex:/^\d*$/,
									allowBlank:false,
									blankText:'Este campo es requerido',
								    regexText:'Valor incorrecto',
									anchor:'85%',
									listeners:{change:OnLongitudChange}
							});
				var Visible  = new Ext.form.ComboBox({
									xtype:'combo',
									fieldLabel: 'Obligatorio',
									store:stVisible,
									hideLabel:false,
									id:"idVisible",
									mode: 'local',
									editable :false,
									triggerAction:'all',
									allowBlank:false,
									forceSelection:true,
									emptyText:'Seleccione visibilidad...',												
									anchor:'85%',
									hiddenName:'visible',
									valueField:'visible',
									displayField:'bol'
							});
				var cf_3 = {columnWidth:.32,layout: 'form',	items: [Longitud,Visible]}
				
				
/**************************FORMULARIO **********************************************/				
				var fm_Campos = new Ext.FormPanel({
									labelAlign: 'top',
									frame:true,
									border:'false',
									items:[{
										layout:'column',
										style:'margin:5 0 0 10',
										items:[cf_1,cf_2,cf_3]
									},{
										xtype:'textarea',
										height:70,
										width:100,
										fieldLabel: 'Descripci&oacute;n',
										name:'descripcion',
										anchor:'100%'
									}]
						});	
/**************************FUNCIONES **********************************************/
				Ext.apply(this,{
								layout:'fit',
								height:200,
								items:[this.girdcampos]
								});

				function OnAdicionarClick(btn){
						params = null;
	   			    	url ="insertar"+referencia;
	   			    	addormodify = true;
				    	mostrarWinFormCampos();
				    	wnFormCampos.setTitle('Adicionar característica');
					};
				function LlenarCampoVpd(){
						var c = win.Cargar();
						
						var d = c.reader.jsonData.datos;

						for ( var i = 0, len = c.reader.jsonData.datos.length; i < len; i++ )
						{
							var valor = vpd.getValue() + d[i].valor;
							valor = i != len - 1 ? valor + " " : valor;
							
							vpd.setValue(valor);
						}							
					}
				
				function OnModificarClick(){
						url ="modificar"+ referencia;
		   				
            			mostrarWinFormCampos();
            			wnFormCampos.setTitle('Modificar características');
            			addormodify = false;
		   				fm_Campos.getForm().loadRecord(rec);
		   				
//		   				win.Cargar().destroy();
		   				
			   			//win.Cargar().baseParams = {idcampo:rec.data.idcampo};
						//win.Cargar().load({params:{start:0,limit:10}});
						
	   					HabilitarValorXdefecto(rec);
	   					
	   					LlenarCampoVpd();

					};
				function onGridLoad(s){
						selmodel.clearSelections();
						SetEstadoBotonesTbarGrid();
					};
				function OnEliminarClick(){
						if(!selmodel.hasSelection())
							mostrarMensaje(1,'Debe seleccionar el que desea eliminar');
						else{
							mostrarMensaje(2,'&iquest; Est&aacute; seguro que desea eliminar '+rec.data.nombre+'?',elimina)
						}
						
						function elimina(btnPresionado){
							params ={idcampo:idcampo,idtabla:idtabla}; 
								if (btnPresionado == 'ok'){
										Ext.Ajax.request({
												url: 'eliminar'+referencia,
												method:'POST',
												params:params,
												callback: function (options,success,response){
																	responseData = Ext.decode(response.responseText);
																	mostrarMensaje(responseData.codMsg,responseData.mensaje);
																	store1.reload();
																	selmodel.clearSelections();
																	SetEstadoBotonesTbarGrid();
												}
										});
								}
				
						}
				
				
				};
				
				function OnSelectRowClick(sm, indiceFila, record){
   					rec = record;
   					idcampo=record.data.idcampo;  
   					SetEstadoBotonesTbarGrid();
   					
   					win.Cargar().baseParams = {idcampo:rec.data.idcampo};
					win.Cargar().load({params:{start:0,limit:10}});
   				};
   				
   				function SetEstadoBotonesTbarGrid(){
   						if(selmodel.hasSelection()){   							
							Ext.getCmp('btnModificar').enable();
							Ext.getCmp('btnEliminar').enable();
						}
						else{
								Ext.getCmp('btnModificar').disable();
								Ext.getCmp('btnEliminar').disable();
						}
   				};
				
				function onTipoDatosSelect(c,r,i){};
				
				function onTipoCamposSelect(c,r,i){
					HabilitarValorXdefecto(r);
				}
				
				function onValorDefectoFocus(){

						
						if(idTipoCampo.value == "combo" /*banderavpd*/){
								win.on({
									'beforehide': onValorDefectoHide,
									scope: this
									});
								//Limpiar la ventana de valores por defecto
								console.info(win.Cargar());
								
								if (addormodify == true)
									win.Cargar().removeAll();
								else
									win.Cargar().reload();
								
								win.show();
						}
					};
				function onValorDefectoHide(){
					ArrayVpd = [];					
					win.Cargar().each(CrearArray,this);
					
					var strtmp = "";
					for (var i = 0; i < ArrayVpd.length; i++){
						strtmp += ArrayVpd[i]["valor"] + " ";
					}
					
					vpd.setValue(strtmp);
					
					}
				
				function onValorDefectoBlur(){};
				
				function OnLongitudChange(){};
				
				function OnNombreChange(){};
	
				function HabilitarValorXdefecto(r){
								vpd.enable();
							if(r.data.tipocampo=='combo'){							
								banderavpd = true;
							}	
							else banderavpd = false;
				}
				
				function mostrarWinFormCampos(){
						if(!wnFormCampos){
							wnFormCampos = new Ext.Window({
												title:'Confeccionar Característica',
												layout:'fit',
												width:450,
												height:330,
												modal:true,
												items:fm_Campos,
												closeAction:'hide',
												listeners:{hide:onWnFormCamposhide,show:OnWnFormCamposRender},
												buttons:[{
															text:'Cancelar',
															icon:perfil.dirImg+'cancelar.png',
															iconCls:'btn',
															handler:OnCancelarClick
														},{
															text:'Aceptar',
															icon:perfil.dirImg+'adicionar.png',
															iconCls:'btn',
															handler:OnAceptarClick
														}]
										});		
				
						}
						wnFormCampos.show(this);
					};
				
				function CrearArray(record){
					ArrayVpd.push({valor:record.data.valor,idvalor:record.data.valor});
					}
				function onWnFormCamposhide(){
						fm_Campos.getForm().reset();
					}
				function OnWnFormCamposRender(f,a){
						//alert(TipoCampo.getValue());	
					}
				function OnAceptarClick(){
										
					if ( idTipoCampo.value != "combo" /*rec.data.tipocampo!='combo'*/ ){
						ArrayVpd = [];
						ArrayVpd.push({valor:vpd.getValue(),idvalor:vpd.getValue()});
						//ArrayVpd.push({valor:record.data.valor,idvalor:record.data.valor});
						banderavpd = false;
						}
					
					banderavpd = true;
					ArrayVpd = Ext.encode(ArrayVpd);
					idbase=idtabla;
					
					params ={idcampo:idcampo,vpdcb:ArrayVpd,idtabla:idbase}; 
					if (fm_Campos.getForm().isValid())
						{
							fm_Campos.getForm().submit(
								{
									url:url,
									params:params,	
									failure: function(form, action){
											mostrarMensaje(action.result.codMsg,action.result.mensaje);
											if(action.result.codMsg != 3)
												{
													store1.reload();
													fm_Campos.getForm().reset();
													wnFormCampos.hide();
											    }
									}
								,waitMsg:'Enviando los datos...'})
							
						}												
						else
						
					/* Ext.Msg.show({
						title:'Mensajede Error',
						msg: 'Por favor verifique nuevamente que hay campo(s) con valor(es) incorrecto(s).',
						buttons: ( Ext.Msg.OK),                   
						animEl: document.body,                   
						icon: Ext.MessageBox.INFO}) */
						
					{ 
							mostrarMensaje(3,'Por favor verifique nuevamente que hay campo(s) con valor(es) incorrecto(s).');
					}
						
				
						
					};
				
				function OnCancelarClick(){
					wnFormCampos.hide();
					fm_Campos.getForm().reset();
				}
		/** Fin del codigo implementado**/			
			}
		});
		
Ext.reg('gdcampos',Erp.ec.GridGestionarCampos);
