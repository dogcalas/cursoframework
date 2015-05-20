
/**
 * @author administrador
 */
 var id_salario;
 
	var st_DenomCargoCivil = new Ext.data.Store({
						    autoLoad: true,						    
						    url: 'mostrarnomcargocivil',
						    reader: new Ext.data.JsonReader({
						        totalProperty: "cant",
						        root: "datos",
						        id: "idcargocivil"    
						    },[{
						        name: 'idcargociv'
						    },{
						         name: 'dencargociv'
						    },{
						         name: 'dencategocup',mapping:'NomCategocup.dencategocup'
						    }]) 
						});
	  var st_EscalaSalarial = new Ext.data.Store({
							    autoLoad: true,
							    url: 'mostrarnomescalasalarial',
							    reader: new Ext.data.JsonReader({
							        totalProperty: "cant",
							        root: "datos",
							        id: "idescalasalarial"    
							    },[{
							        name: 'idescalasalarial'
							    },{
							         name: 'denominacion'
							    },{
							         name: 'abreviatura'
							    }])
							});
	var st_Responsabilidad = new Ext.data.Store({
		    autoLoad: true,
		    url: 'mostrarcategoriasciviles',
		    reader: new Ext.data.JsonReader({
		       totalProperty: "cant",
		        root: "datos",
		        id: "idcategcivil"    
		    },[{
		        name: 'idcategcivil'
		         },{
		         name: 'dencategcivil'
		         }] 
		)
		});
	var st_GrupoComplejidad = new Ext.data.Store({
									  autoLoad: true,
									  url: 'mostrarnomgrupocomple',
									  reader: new Ext.data.JsonReader({
										   totalProperty: "cant",
											root: "datos",
											id: "idgrupocomplejidad"    
										},[{
											name: 'idgrupocomplejidad'
										},{
										 	name: 'denominacion',mapping:'NomGrupocomple.denominacion'
										 }])
								});
	var st_Clasifcargo = new Ext.data.Store({
								autoLoad: true,
								url: 'mostrarnomclasificacion',
								reader: new Ext.data.JsonReader({
								    totalProperty: "cant",
									root: "datos",
									id: "idclasificacion"    
								},[{
									name: 'idclasificacion'
								},{
									 name: 'denominacion'
								}])
							});
	/*var st_DenomEspecialidad = new Ext.data.Store({
										autoLoad: true,
										url: 'mostrarespecialidades',
										reader: new Ext.data.JsonReader({
										    totalProperty: "cant",
											root: "datos",
											id: "idespecialidad"    
										},[{
											name: 'idespecialidad'
										},{
											 name: 'denespecialidad'
										}])
									});
	*/
	var st_DenomTipoCifra = new Ext.data.Store({
									autoLoad: true,
									url: 'mostrarcifras',
									reader: new Ext.data.JsonReader({
									    totalProperty: "cant",
										root: "datos",
										id: "idtipocifra"    
									},[{
										name: 'idtipocifra'
									},{
										 name: 'dentipocifra'
									}])
								});
	var st_GradoMilitar = new Ext.data.Store({
								autoLoad: true,
								url: 'mostrarnomgradomtar',
								reader: new Ext.data.JsonReader({
									totalProperty: "cant",
									root: "datos",
									id: "idgradomilit"    
								},[{
									name: 'idgradomilit'
								},{
									name: 'dengradomilit'
								}])
							});
	var st_DenomCargoMilitar = new Ext.data.Store({
										autoLoad: true,
										url: 'mostrarnomcargomtar',
										reader: new Ext.data.JsonReader({
											totalProperty: "cant",
											root: "datos",
											id: "idgradomilit"    
										},[{
											name: 'idcargomilitar'
										},{
											name: 'dencargomilitar'
										}])
									});
Civil = function(){
	var WinTreeEspecialidad;
	var cb_CargoCivil = new Ext.form.ComboBox({
						//xtype:'combo',
						fieldLabel: 'Denominación del cargo',
						//id:"idcargocivil",
						allowBlank:false,
						editable :false,
						triggerAction:'all',
						forceSelection:true,
						emptyText:'Seleccione el tipo..',
						hideLabel:false,
						autoCreate: true,
						mode: 'local',
						forceSelection: true,
						anchor:'93%',
						store:st_DenomCargoCivil,
						displayField:'dencargociv',
						valueField:'idcargociv',
						hiddenName:'idcargociv',
						listeners:{select:OnDenominacionSelect}
					});
					
  
	var cb_escalaSalarial = new Ext.form.ComboBox({
								//xtype:'combo',
								fieldLabel: 'Escala salarial',
								store:st_EscalaSalarial,
								editable :false,
								triggerAction:'all',
								allowBlank:false,
								forceSelection:true,
								emptyText:'Seleccione el tipo..',
								hideLabel:false,
								autoCreate: true,
								mode: 'local',
								forceSelection: true,
								anchor:'93%',
								//id:"especialidad",
								valueField:'idescalasalarial',
								hiddenName:'idescalasalarial',
								displayField:'denominacion',
								listeners:{select:OnEscalaSalarialSelect}
								});
	var nf_Salario = new Ext.form.NumberField({
						xtype:'numberfield',
						fieldLabel: 'Salario',
						maxLength:8,
						readOnly:true,
						maxLengthText:'El máximo de caracteres es 8',
						name: 'salario',
						regex:/^\d*$/,
						regexText:'Valor incorrecto',
						allowBlank:false,
						blankText:'Este campo es requerido.',
						anchor:'93%'
						});
	var nf_cantidad =  new Ext.form.NumberField({
						xtype:'numberfield',
						fieldLabel: 'Cantidad',
						maxLength:8,
						maxLengthText:'El mÃ¡ximo de caracteres es 8',
						name: 'ctp',
						regex:/^\d*$/,   
						regexText:'Valor incorrecto',
						allowBlank:false,
						blankText:'Este campo es requerido.',
						anchor:'93%'
						});
	var tf_idsalario = new Ext.form.TextField({
						xtype:'textfield',
						fieldLabel: 'Categoría ocupacional',
						hideLabel:true,
						hidden:true,
						name: 'idsalario'
			});
	var cb_Cifra = new Ext.form.ComboBox({							
					fieldLabel: 'Cifra',
					allowBlank:false,
					editable :false,
					triggerAction:'all',
					forceSelection:true,
					emptyText:'Seleccione..',
					hideLabel:false,
					autoCreate: true,
					mode: 'local',
					forceSelection: true,
					anchor:'93%',
					store:st_DenomTipoCifra,
					id:"tipocifra",
					valueField:'idtipocifra',
					hiddenName:'idtipocifra',
					displayField:'dentipocifra'
					});

	var col1 = {columnWidth:.32,layout: 'form',
				items: [cb_CargoCivil,cb_escalaSalarial ,nf_Salario,nf_cantidad,cb_Cifra,tf_idsalario]
				};

	var tf_CatOcup = new Ext.form.TextField({
						xtype:'textfield',
						fieldLabel: 'Categoría ocupacional', 
						name: 'dencategocup', 
						allowBlank:false,
						readOnly: true,
						anchor:'93%',
						regex:/^(\W|\w){1,255}$/,
						maskRe:/^(\W|\w){1,255}$/  
			});
		
	var cb_Reponsabilidad =  new Ext.form.ComboBox({
								fieldLabel: 'Reponsabilidad',
								store:st_Responsabilidad,
								editable :false,
								triggerAction:'all',
								//allowBlank:false,
								forceSelection:true,
								emptyText:'Seleccione el tipo..',
								hideLabel:false,
								autoCreate: true,
								mode: 'local',
								forceSelection: true,
								anchor:'93%',
								//id:"idcategcivil",
								valueField:'idcategcivil',
								hiddenName:'idcategcivil',
								displayField:'dencategcivil',
								listeners:{select:OnReponsabilidadSelect}
								});
		var tf_Especialidad = new Ext.form.TextField({
									xtype:'textfield',
									fieldLabel: 'Especialidad', 
									name: 'denespecialidad', 
									allowBlank:false,
									readOnly: true,
									anchor:'93%',
									//regex:/^(\W|\w){1,255}$/,
									//maskRe:/^(\W|\w){1,255}$/ 
									listeners :{focus:function(){WinFnTreeEspecialidad();}				
												}
						});
		var tf_idEspecialidad = new Ext.form.TextField({
						xtype:'textfield',
						fieldLabel: 'sd',
						hideLabel:true,
						hidden:true,
						name: 'idespecialidad'
			});
		var treeEspecialidad = new Ext.tree.TreePanel({        
	        			autoScroll:true,					
	        			width: 200,
	        			minSize: 175,
	        			maxSize: 400,
	        			height:600,	        			
	       				margins:'0 0 0 2',                    
	        			layoutConfig:{  animate:true   },
						region:'west',						
	        			enableDD:false,	        			
	        			containerScroll: true, 
	        			loader: new Ext.tree.TreeLoader({dataUrl:'hijoespecialidad'}),
	        			root:new Ext.tree.AsyncTreeNode({
			       					text: 'Especialidad',
			        				draggable:false,
			        				id:'idespecialidad'
						}),
                        listeners:{click:function(n){							   
										    tf_Especialidad.setValue(n.text);
											tf_idEspecialidad.setValue(n.id);
											WinTreeEspecialidad.hide();
							  			}
						
						}						
	        			
			});
		
		var nf_ctg =  new Ext.form.NumberField({
						xtype:'numberfield',
						fieldLabel: 'Ctg',
						maxLength:8,
						maxLengthText:'El mÃ¡ximo de caracteres es 8',
						name: 'ctg',
						regex:/^\d*$/,   
						regexText:'Valor incorrecto',
						allowBlank:false,
						blankText:'Este campo es requerido.',
						anchor:'93%'
						});	

		var col2 = { 
				columnWidth:.32,layout: 'form',
				items: [tf_CatOcup,cb_Reponsabilidad,tf_Especialidad,nf_ctg,tf_idEspecialidad]
				}; 



		
		var cb_complejidad = new Ext.form.ComboBox({		
								fieldLabel: 'Grupo de complejidad',
								store:st_GrupoComplejidad,
								disable: true,
								editable :false,
								triggerAction:'all',
								allowBlank:false,
								forceSelection:true,
								emptyText:'Seleccione el tipo..',
								autoCreate: true,
								mode: 'local',
								forceSelection: true,
								anchor:'93%',
								valueField:'idgrupocomplejidad',
								hiddenName:'idgrupocomplejidad',
								displayField:'denominacion',
								listeners:{select:OnComplejidadSelect}
								});
		
		var cb_Clasifcargo = new Ext.form.ComboBox({
								fieldLabel: 'Clasificación de cargo',
								store:st_Clasifcargo,
								editable :false,
								triggerAction:'all',
								allowBlank:false,
								forceSelection:true,
								emptyText:'Seleccione el tipo..',
								hideLabel:false,
								autoCreate: true,
								mode: 'local',
								forceSelection: true,
								anchor:'93%',
								valueField:'idclasificacion',
								hiddenName:'idclasificacion',
								displayField:'denominacion'
								});
		var df_fi = new Ext.form.DateField({
						fieldLabel: 'Fecha inicio',
						readOnly :true,
						name: 'fechaini',
						format :'d/m/Y',
						value : new Date(),
						anchor:'95%'						
						});
		var tf_fin = new Ext.form.DateField({		
						fieldLabel: 'Fecha fin',
						readOnly :true,
						format :'d/m/Y',
						id: 'fechafin',
						anchor:'95%'
						});
		var col3 = {
			columnWidth:.32,layout: 'form',
			items: [cb_complejidad,cb_Clasifcargo,df_fi,tf_fin]
			};
		function WinFnTreeEspecialidad(){
				   if (!WinTreeEspecialidad) {
		                WinTreeEspecialidad = new Ext.Window({
								                    title: 'Especialidad',
								                    layout:'fit',
								                    items: [treeEspecialidad],                                        
								                    width: 300,
													modal:true,
								                    height: 400,
								                    closeAction: 'hide',
								                    plain: true
								                
								                });
		            }
		            WinTreeEspecialidad.show();
			}	
		function OnDenominacionSelect(c,r,i){
				tf_CatOcup.setValue(r.data.dencategocup);
				st_GrupoComplejidad.baseParams={idcargociv:r.data.idcargociv};
				st_GrupoComplejidad.load();
		};
		function OnComplejidadSelect(c,r,i){
			if(cb_escalaSalarial.getValue()!=''){
				var idescalaslrial = r.data.idgrupocomplejidad;
				var idgpocomple = cb_escalaSalarial.getValue();
				BuscarSalario(idgpocomple,idescalaslrial);
			}
		};
		function OnEscalaSalarialSelect(c,r,i){
			if(cb_complejidad.getValue()!=''){
				var idescalaslrial = r.data.idescalasalarial;
				var idgpocomple = cb_complejidad.getValue();
				BuscarSalario(idgpocomple,idescalaslrial);
			}
		};
		function OnReponsabilidadSelect(c,r,i){
			st_GrupoComplejidad.baseParams={};
			st_GrupoComplejidad.load();
			};
		function BuscarSalario(idgpocomple,idescalaslrial){
				var params ={idgrupocomplejidad:idgpocomple,idescalasalarial:idescalaslrial};
				Ext.Ajax.request({
									url:'buscarsalarioporgrupoescala',
									method:'POST',
									params:params,
									callback:OnCallBakBuscarSalario								
							});
		};
		function OnCallBakBuscarSalario(options,success,response){
				var r =Ext.decode(response.responseText);
				if(r.cant==0){
					nf_Salario.reset();
					tf_idsalario.reset();
					Ext.Msg.show({
						title:'Error',
						msg: 'No existe salario para el grupo de complejidad y escala salarial seleccionada.',
						buttons: (Ext.Msg.OK ),                   
						animEl: document.body,                   
						icon: Ext.MessageBox.ERROR})
					
				}
				else{
				nf_Salario.setValue(r.datos[0].salario)	
				tf_idsalario.setValue(r.datos[0].idsalario);
				}
		};
		
this.item = [col1,col2,col3];
a = [];
a.length

};//fin del cargo civil

Militar=function(){
	
		var WinTreeEspecialidad;
		
		var nf_Estado =  new Ext.form.NumberField({							
							fieldLabel: 'Estado',
							maxLength:3,
							maxLengthText:'El m&aacute;ximo de caracteres es 3',
							name: 'estado',
							regex:/^\d*$/,
							regexText:'Valor incorrecto',
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'
							});

		var tf_Especialidad = new Ext.form.TextField({
									xtype:'textfield',
									fieldLabel: 'Especialidad', 
									name: 'denespecialidad', 
									allowBlank:false,
									readOnly: true,
									anchor:'93%',
									//regex:/^(\W|\w){1,255}$/,
									//maskRe:/^(\W|\w){1,255}$/ 
									listeners :{focus:function(){WinFnTreeEspecialidad();}				
												}
						});
		var tf_idEspecialidad = new Ext.form.TextField({
						xtype:'textfield',
						fieldLabel: 'sd',
						hideLabel:true,
						hidden:true,
						name: 'idespecialidad'
			});
		var treeEspecialidad = new Ext.tree.TreePanel({        
	        			autoScroll:true,					
	        			width: 200,
	        			minSize: 175,
	        			maxSize: 400,
	        			height:600,	        			
	       				margins:'0 0 0 2',                    
	        			layoutConfig:{ animate:true   },
						region:'west',						
	        			enableDD:false,	        			
	        			containerScroll: true, 
	        			loader: new Ext.tree.TreeLoader({dataUrl:'hijoespecialidad'}),
	        			root:new Ext.tree.AsyncTreeNode({
			       					text: 'Especialidad',
			        				draggable:false,
			        				id:'idespecialidad'
						}),
                        listeners:{click:function(n){							   
										    tf_Especialidad.setValue(n.text);
											tf_idEspecialidad.setValue(n.id);
											WinTreeEspecialidad.hide();
							  			}
						
						}						
	        			
			});
		/*var cb_Especialidad = new Ext.form.ComboBox({								
								fieldLabel: 'Especialidad',
								store:st_DenomEspecialidad,
								editable :false,
								triggerAction:'all',
								allowBlank:false,
								forceSelection:true,
								emptyText:'Seleccione el tipo..',
								hideLabel:false,
								autoCreate: true,
								mode: 'local',
								forceSelection: true,
								anchor:'93%',
								id:"especialidad",
								valueField:'idespecialidad',
								hiddenName:'idespecialidad',
								displayField:'denespecialidad'
								});*/
		
		var cb_Cifra = new Ext.form.ComboBox({							
							fieldLabel: 'Cifra',
							allowBlank:false,
							editable :false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:'Seleccione..',
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_DenomTipoCifra,
							id:"tipocifra",
							valueField:'idtipocifra',
							hiddenName:'idtipocifra',
							displayField:'dentipocifra'
							});
		var Datos=[['1', 'Si'],['0', 'No']];
		
		var st_EscalaMando = new Ext.data.SimpleStore({
				fields: ['tienemando', 'bol'],
				data : Datos
			});
    
		var cb_EscalaMando =  new Ext.form.ComboBox({								
								fieldLabel: 'Escala de mando',
								hideLabel:false,
								mode: 'local',
								allowBlank:false,
								editable :false,
								triggerAction:'all',
								forceSelection:true,
								emptyText:'Seleccione visibilidad...',
								id: 'visible',
								anchor:'93%',
								store:st_EscalaMando,
								valueField:'tienemando',
								hiddenName:'tienemando',
								displayField:'bol'
								});

		var col1 = {
			columnWidth:.32,layout: 'form',
			items: [nf_Estado,tf_Especialidad,cb_Cifra,cb_EscalaMando,tf_idEspecialidad]
			};


		var nf_CTP = new Ext.form.NumberField({		
						fieldLabel: 'CTP',
						maxLength:3,
						maxLengthText:'El m&aacute;ximo de caracteres es 3',
						name: 'ctp',
						regex:/^\d*$/,   //   /^([a-zA-Zï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½\d]+ ?[a-zA-Zï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½\d]*)+$/,
						regexText:'Valor incorrecto',
						allowBlank:false,
						blankText:'Este campo es requerido.',
						anchor:'93%'
						});
		var nf_Salario = new Ext.form.NumberField({		
							fieldLabel: 'Salario',
							maxLength:8,
							readOnly:false,
							maxLengthText:'El máximo de caracteres es 8',
							name: 'salario',
							regex:/^\d*$/,
							regexText:'Valor incorrecto',
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'
							});

		var df_inicio = new Ext.form.DateField({
							fieldLabel: 'Fecha inicio',
							readOnly :true,
							name: 'fechaini',
							format :'d/m/Y',
							value : new Date(),
							anchor:'95%'
							});

		var cb_GradoMilitar =  new Ext.form.ComboBox({
									fieldLabel: 'Grado',
									allowBlank:false,
									editable :false,
									triggerAction:'all',
									forceSelection:true,
									emptyText:'Seleccione ..',
									hideLabel:false,
									autoCreate: true,
									mode: 'local',
									forceSelection: true,
									anchor:'93%',
									store:st_GradoMilitar,
									id:"grado",
									displayField:'dengradomilit',
									valueField:'idgradomilit',
									hiddenName:'idgradomilit'
									});

		var col2 = {
			columnWidth:.32,layout: 'form',
			items: [nf_CTP,nf_Salario,df_inicio,cb_GradoMilitar]
			};

		var nf_CTG = new Ext.form.NumberField({		 
						fieldLabel: 'CTG',
						maxLength:3,
						maxLengthText:'El m&aacute;ximo de caracteres es 3',
						id: 'ctg',
						name:'ctg',
						regex:/^\d*$/,               // /^([a-zA-Zï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½\d]+ ?[a-zA-Zï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½\d]*)+$/,
						regexText:'Valor incorrecto',
						allowBlank:false,
						blankText:'Este campo es requerido.',
						anchor:'93%'
						});
		var  nf_Orden = new Ext.form.NumberField({		 
							fieldLabel: 'Orden',
							maxLength:3,
							maxLengthText:'El m&aacute;ximo de caracteres es 3',
							id: 'orden',
							regex:/^\d*$/,
							allowBlank:false,
							blankText:'Este campo es requerido.',
							regexText:'Valor incorrecto',
							anchor:'93%'
							});

		var cb_CargoMilitar = new Ext.form.ComboBox({ 
								fieldLabel: 'Cargo militar',
								id:"idcargocivil",
								editable :false,
								allowBlank:false,
								triggerAction:'all',
								forceSelection:true,
								emptyText:'Seleccione el tipo..',
								hideLabel:false,
								autoCreate: true,
								mode: 'local',
								forceSelection: true,
								anchor:'93%',
								store:st_DenomCargoMilitar,
								displayField:'dencargomilitar',
								valueField:'idcargomilitar',
								hiddenName:'idcargomilitar'
								});
		var df_fi = new Ext.form.DateField({		 
						fieldLabel: 'Fecha fin',
						readOnly :true,
						format :'d/m/Y',
						id: 'fechafin',
						anchor:'95%'
						});
		var col3 = {
			columnWidth:.32,layout: 'form',
			items: [nf_CTG,nf_Orden,cb_CargoMilitar,df_fi]
			};
		function WinFnTreeEspecialidad(){
				   if (!WinTreeEspecialidad) {
		                WinTreeEspecialidad = new Ext.Window({
								                    title: 'Especialidad',
								                    layout:'fit',
								                    items: [treeEspecialidad],                                        
								                    width: 300,
													modal:true,
								                    height: 400,
								                    closeAction: 'hide',
								                    plain: true
								                
								                });
		            }
		            WinTreeEspecialidad.show();
			}
		this.item = [col1,col2,col3];
};//fin de la clase militar

gestionarCargo = function(){

	
	var wn_FormCargos; //usada para crear la ventana
	var Record_ES;
	var rec ; // usada para guardar el record del grid
	
	var idpadre;
	
	var referencia, nombre, AddMod; // guarda parate de la url
	
	var url;  // guarada la url completa
	
	var params,Cargoses, activo; // guarada los parametros que se le pasan 
	
	var st_Cargos; // usada para crear el store
	
	var gd_Cargos; // usada para crear grid
	
	var bt_AdicionarCargos,bt_ModificarCargos,// usada para crear
	
	bt_EliminarCargos,bt_AyudaCargos;  // los botones
	
	var item; // usada para aignarle el item de cada objeto
	
	var objectid; // usada para asignarle el id del objeto
	
	var object; //uasada para guad=rdar el objeto
	
	var fm_Cargos, // usada para crear el formulario
	    cm_Cargos, //usada para asiganar las columnas del objeto
	    nemury,wn_GestionarCargo,
	    
	    rc_Cargos;// usada para asignarle el record del objeto   
	
	var title_WinGestionarCargos;	 
		/**VARIABLES PUBLICAS*/
	this.id;
		
	this.prueba;	
		
   	 	
   	var	menu =new Ext.menu.Menu({
					items:[{
							text: 'Cargo civil',
							id:'Civil',
							handler: function(){ Adicionar('civil'); ActionAfterInsert = AfterInsert}
						},{
							text: 'Cargo militar',
							id:'Militar',
							handler: function(){ Adicionar('militar'); ActionAfterInsert = AfterInsert;	}
						}]
				});	
		bt_AdicionarCargos = new Ext.Button({icon:perfil.dirImg+'adicionar.png',iconCls:'btn',id:"Adicionarpsto",												 
									   //text:perfil.etiquetas.lbBtnAdicionar,
									   text:'Adicionar',
									 	menu:menu
								});
		bt_ModificarCargos = new Ext.Button({icon:perfil.dirImg+'modificar.png',iconCls:'btn',id:"Modificarpsto",disabled:true,										 
									  handler:Onbt_ModificarCargosClick,
									  //text:perfil.etiquetas.lbBtnModificar
									  text:'Modificar'
									  
								});
		bt_EliminarCargos = new Ext.Button({icon:perfil.dirImg+'eliminar.png',iconCls:'btn',id:"Eliminarpsto",disabled:true,												 
									 handler:Onbt_EliminarCargosClick,
									 //text:perfil.etiquetas.lbBtnEliminar
									   text: 'Eliminar'
								});
		
		bt_AyudaCargos = new Ext.Button({icon:perfil.dirImg+'ayuda.png',iconCls:'btn',id:"Ayuda",
								  handler:Onbt_AyudaCargosClick
							   });
		var sm_MostrarCargos = new Ext.grid.RowSelectionModel({
										 singleSelect:false,
										 listeners:{rowselect:On_RowClick}
									});	
		st_Cargos =  new Ext.data.Store({
											url:'mostrarcargos',											
											reader:new Ext.data.JsonReader({
												root:'datos',
												id:'idcargo',
												totalProperty:'cant'
												},[
													{name: 'idcargo'},											
													{name: 'idespecialidad'},
													{name: 'tipocargo'},
													{name: 'denespecialidad' ,mapping:'NomEspecialidad.denespecialidad'},
													{name: 'dentipocifra' ,mapping:'NomTipocifra.dentipocifra'},											
													{name: 'ctp'},
													{name: 'ctg'},
													{name: 'fechaini',type:'date',dateFormat: 'Y-m-d'},
													{name: 'fechafin',type:'date',dateFormat: 'Y-m-d'},
													{name: 'estado'}
											
											])
						});
		
		cm_Cargos = [
					{header: 'Especialidad', dataIndex: 'denespecialidad',id:'expandir'},
					{header: 'Cifra', width:50, dataIndex: 'dentipocifra'},
					{header: 'CTP', width:40, dataIndex: 'ctp'},
					{header: 'CTG', width:40, dataIndex: 'ctg'},
					{header: 'Orden', width:60, dataIndex: 'orden'},
					{header: 'Estado', width:40, dataIndex: 'estado'},
					{header: 'Fecha inicio', width:80, dataIndex: 'fechaini',renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{header: 'Fecha fin', width:80, dataIndex: 'fechafin',renderer: Ext.util.Format.dateRenderer('d/m/Y')}//,{header: 'Salario', width:100, dataIndex: 'fechafin'}
				];
		gd_Cargos = new Ext.grid.GridPanel({
										frame:true,
										iconCls:'icon-grid',
										autoExpandColumn:'expandir',
										store:st_Cargos,
										sm:sm_MostrarCargos,
										//loadMask:{msg :perfil.etiquetas.lbMsgCargando},
										loadMask:{msg :'cargando'},
										columns: cm_Cargos,
										tbar:[bt_AdicionarCargos,bt_ModificarCargos,bt_EliminarCargos,'-',bt_AyudaCargos],
										bbar: new Ext.PagingToolbar({
														pageSize: 10,
														store: st_Cargos,
														displayInfo: true,
														//displayMsg: perfil.etiquetas.lbMsgResultados,
														displayMsg: 'Resultados',
														//emptyMsg: perfil.etiquetas.lbMsgNohayresultadosparamostrar
														emptyMsg: 'no hay resultados para mostrar'
											})
							});
		/**FUNCIONES PUBLICAS*/		
		this.Adicionar = function(id,param,AfterInsert){
			Adicionar(id,param);
			 ActionAfterInsert = AfterInsert;
		};
		this.Modificar = function(param,AfterInsert){
			 Modificar(param);
			 ActionAfterInsert = AfterInsert;
		};
		this.Eliminar = function(param,actionAfterDelete){
			 Eliminar(param,actionAfterDelete);
		};
		this.SetTitle= function(titulo){
			SetTitle(titulo);
		};
		/**FUNCIONES PRIVADAS*/
		AfterInsert = function(){
			st_Cargos.reload();	
			};
		function Adicionar(id,param){
				
				OnSelecCargo(id);
				url='insertarcargo'+referencia;
				params=param;
				fm_Cargos = new Ext.FormPanel({
								 labelAlign: 'top',
								 frame:true,
								 autoHeight:true,
								 border:'false',
								 items:[{layout:'column',items: item }]
								 
						});
				mostrarWin_FormCargos();
		}
		function Modificar(param){
			params = param;
			BuscarModificar(param.tipocargo);
			OnSelecCargo(param.tipocargo);
			url='modificarcargo'+referencia;
			fm_Cargos = new Ext.FormPanel({
									labelAlign: 'top',
									frame:true,
									reader: new Ext.data.JsonReader({
										root:"datos",
										successProperty:"success"
									},Record_ES),
								 autoHeight:true,
								 border:'false',
								 items:[{layout:'column',items: item }]
								 
						});
			//alert(Record_ES);
			mostrarWin_FormCargos();
			fm_Cargos.getForm().load({url:'mostrardatoscargo',params:params,waitMsg:'Cargando datos'});
		};
		function SetTitle(titulo){
			title_WinGestionarCargos = titulo;
		};
		function Eliminar(param,actionAfterDelete){		
						params = param;
						url ='eliminarcargo';
						//mostrarMensaje(2,'¿'+perfil.etiquetas.lbMsgseguroquedeseaeliminar + '?',elimina)
						mostrarMensaje(2,'¿ Seguro que desea eliminar ?',elimina)
						function elimina(btnPresionado){							   
								if (btnPresionado){										
										Ext.Ajax.request({
												url:url,
												method:'POST',
												params:params,
												callback: function (options,success,response){
														responseData = Ext.decode(response.responseText);
														mostrarMensaje(responseData.codMsg,responseData.mensaje);
														actionAfterDelete();
												}										
										});								
								}
				
						}
		};
		
		function OnSelecCargo(id){
		   	 		switch(id){
			   	 		case 'militar':
			   	 			 object = new Militar();
			   	 		break;
			   	 		case 'civil':
			   	 			 object = new Civil();
			   	 		break;		
		   	 		}
			   	 	item = object.item;
			   	 	referencia = id;			   	 	
   	 		};	
   	 		/*
   	 		 * ,{
											name: 'denespecialidad' ,mapping:'NomEspecialidad.denespecialidad'
										},{
											name: 'dentipocifra' ,mapping:'NomTipocifra.dentipocifra'
										}
   	 		 */
   	 	function BuscarModificar(id){
		   	 		switch(id){
			   	 		case 'militar':
			   	 			Record_ES = Ext.data.Record.create([{
											
											name: 'idcargo',mapping:'viejo[0].idcargo'
										},{
											name: 'idestructuraop',mapping:'viejo[0].idestructuraop'
										},{
											name: 'idespecialidad',mapping:'nuevo.idespecialidad'
										},{
											name: 'denespecialidad',mapping:'nuevo.denespecialidad'
										},{
											name:'idtipocifra',mapping:'viejo[0].idtipocifra'
										},{
											name: 'ctp',mapping:'viejo[0].ctp'
										},{
											name: 'ctg',mapping:'viejo[0].ctg'
										},{
											name: 'orden',mapping:'viejo[0].orden'
										},{
											name: 'estado',mapping:'viejo[0].estado'
										},{
											name: 'fechaini',mapping:'viejo[0].fechaini'
										},{
											name: 'fechafin',mapping:'viejo[0].fechafin'
										},{
											name: 'salario',mapping:'viejo[0].DatCargomtar.salario'
										},{
											name: 'idsalario',mapping:'viejo[0].DatCargomtar.idsalario'
										},{
											name: 'tienemando',mapping:'viejo[0].DatCargomtar.escadmando'
										},{
											name: 'idgradomilit',mapping:'viejo[0].DatCargomtar.idgradomilit'
										},{
											name: 'idcargomilitar',mapping:'viejo[0].DatCargomtar.idcargomilitar'
										}]);
			   	 		break;
			   	 		case 'civil':
			   	 			 Record_ES = Ext.data.Record.create([{
			   	 			 				name: 'idcargo',mapping:'viejo[0].idcargo'
			   	 			 			},{
			   	 			 				name: 'idestructuraop',mapping:'viejo[0].idestructuraop'
			   	 			 			},{
			   	 			 				name: 'idespecialidad',mapping:'nuevo.idespecialidad'
			   	 			 			},{
			   	 			 				name: 'denespecialidad',mapping:'nuevo.denespecialidad'
			   	 			 			},{
			   	 			 				name:'idtipocifra',mapping:'viejo[0].idtipocifra'
			   	 			 			},{
			   	 			 				name: 'ctp',mapping:'viejo[0].ctp'
			   	 			 			},{
			   	 			 				name: 'ctg',mapping:'viejo[0].ctg'
			   	 			 			},{
			   	 			 				name: 'orden',mapping:'viejo[0].orden'
			   	 			 			},{
			   	 			 				name: 'estado',mapping:'viejo[0].estado'
			   	 			 			},{
			   	 			 				name: 'fechaini',mapping:'viejo[0].fechaini'
			   	 			 			},{
			   	 			 				name: 'fechafin',mapping:'viejo[0].fechafin'
			   	 			 			},{
			   	 			 				name: 'salario',mapping:'viejo[0].DatCargocivil.NomSalario.salario'
			   	 			 			},{
											name: 'idsalario',mapping:'viejo[0].DatCargocivil.NomSalario.idsalario'
										},{
			   	 			 				name: 'idcargociv',mapping:'viejo[0].DatCargocivil.idcargociv'
			   	 			 			},{
			   	 			 				name: 'idcategcivil',mapping:'viejo[0].DatCargocivil.idcategcivil'
			   	 			 			},{
			   	 			 				name: 'dencategocup',mapping:'viejo[0].DatCargocivil.NomCargocivil.NomCategocup.dencategocup'
			   	 			 			},{
			   	 			 				name: 'idgrupocomplejidad',mapping:'viejo[0].DatCargocivil.NomCargocivil.idgrupocomplejidad'
			   	 			 			},{
			   	 			 				name: 'idescalasalarial',mapping:'viejo[0].DatCargocivil.NomEscalasalarial.idescalasalarial'
			   	 			 			},{
			   	 			 				name: 'idclasificacion',mapping:'viejo[0].DatCargocivil.idclasificacion'
			   	 			 			}]);
			   	 		break;		
		   	 		}
		
   	 		}
		
		function On_RowClick(sm, indiceFila, record){
					rec = record;
   					SetEstadoBotonesTbarGrid();
   				};
			
		function Onbt_AyudaCargosClick(btn){};
		
   		function Onbt_ModificarCargosClick(btn){
   					var param = {idcargo:rec.data.idcargo,tipocargo:rec.data.tipocargo};
	   				Modificar(param);	   				
	   			};

	   	AfterDelete = function(){
					st_Cargos.reload();
					sm_MostrarCargos.clearSelections();
					SetEstadoBotonesTbarGrid();	
			};
	   	
	   	function Onbt_EliminarCargosClick(){
					param = {idcargo:rec.data.idcargo};
					Eliminar(param,AfterDelete);
					
				};
    
   		function TieneCargosSeleccionados(){
   				return (sm_MostrarCargos.hasSelection());
   			};
		
   		function SetEstadoBotonesTbarGrid(){
			if(TieneCargosSeleccionados()){
					bt_ModificarCargos.enable();
					bt_EliminarCargos.enable();
				}
			else{
					bt_ModificarCargos.disable();
					bt_EliminarCargos.disable();
				}
		}
		
		function mostrarWin_FormCargos(){					
					
						wn_FormCargos = new Ext.Window({
											title: 'asdasda',
											layout:'fit',
											width:500,
											autoHeight:true,
											bodyStyle:'padding:5px;',
											items:fm_Cargos,
											modal:true,
											buttons:											
											[{
												//text:perfil.etiquetas.lbMsgCancelar,
												text:'Cancelar',
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												handler:OnCancelarClick
											},{
												//text:perfil.etiquetas.lbMsgAceptar,
												text:'Aceptar',
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												handler:OnAceptarClick
										  }]
								});
					wn_FormCargos.setTitle(title_WinGestionarCargos);
					wn_FormCargos.show(this);
				}
		
		
		function OnAceptarClick(btn){
	    				if (fm_Cargos.getForm().isValid())
							{
							fm_Cargos.getForm().submit({
									url:url,
									params:params,	
									failure: function(form, action){
											mostrarMensaje(action.result.codMsg,action.result.mensaje);
											if(action.result.codMsg != 3 && !activo){
													fm_Cargos.getForm().reset();
													wn_FormCargos.destroy();
													ActionAfterInsert();
											    }
									}
								//,waitMsg:perfil.etiquetas.lbMsgEnviandolosdatos})
								,waitMsg:'Enviando los datos'
								})
							
						}												
						else
						
					Ext.Msg.show({
						//title:perfil.etiquetas.lbTitMensajedeError,
						title:MensajedeError,
						//msg: perfil.etiquetas.lbMsgPorfavorverifiquecamposincorrectos,
						msg: Porfavorverifiquecamposincorrectos,
						buttons: (Ext.Msg.OK ),                   
						animEl: document.body,                   
						icon: Ext.MessageBox.ERROR})
	    		};		
				
		function OnCancelarClick(){
						fm_Cargos.getForm().reset();
						wn_FormCargos.destroy();
		}

			/**FUNCION PUBLICA QUE MUSTRA EL GRID CON LOS CARGOS
			 * Y A TRAVES DE ELLA SE ACCEDE AL CASO DE USO GESTIONAR CARGO* 
			 */
		this.mostrarWin_gestionarCargo = function (){
				id = this.id;
				var tit = this.titulo_W;
				st_Cargos.baseParams = {idop:id};
				st_Cargos.load({params:{start:0,limit:10}})
					if(!wn_GestionarCargo){						
							wn_GestionarCargo = new Ext.Window({
												title:'Definir cargos',
												layout:'fit',
												width:600,
												modal:true,
												height:300,
												closeAction:'hide',
												items:gd_Cargos
											});
					
					}
					//wn_GestionarCargo.setTitle(titulo);
				    wn_GestionarCargo.show(this);
				}
		

}
 /*
  * 
  * var st_GradoMilitar = new Ext.data.Store({
    autoLoad: true,
    url: 'mostrarnomgradomtar',
    reader: new Ext.data.JsonReader({
       totalProperty: "cant",
        root: "datos",
        id: "idgradomilit"    
    },[{
        name: 'idgradomilit'
         },{
         name: 'dengradomilit'
         }] 
)
});
  *var st_DenomEspecialidad = new Ext.data.Store({
    autoLoad: true,
    url: 'mostrarespecialidades',
    reader: new Ext.data.JsonReader({
       totalProperty: "cant",
        root: "datos",
        id: "idespecialidad"    
    },[{
        name: 'idespecialidad'
         },{
         name: 'denespecialidad'
         }] 
)
});

var st_DenomTipoCifra = new Ext.data.Store({
    autoLoad: true,
    url: 'mostrarcifras',
    reader: new Ext.data.JsonReader({
       totalProperty: "cant",
        root: "datos",
        id: "idtipocifra"    
    },[{
        name: 'idtipocifra'
         },{
         name: 'dentipocifra'
         }] 
)
});
  * var st_DenomCargoMilitar = new Ext.data.Store({
    autoLoad: true,
    url: 'mostrarnomcargomtar',
    reader: new Ext.data.JsonReader({
       totalProperty: "cant",
        root: "datos",
        id: "idgradomilit"    
    },[{
        name: 'idcargomilitar'
         },{
         name: 'dencargomilitar'
         }] 
)
});
var Datos=[['1', 'Si'],['0', 'No']];

var st_EscalaMando = new Ext.data.SimpleStore({
        fields: ['tienemando', 'bol'],
        data : Datos
    });
  *var cb_CargoMilitar = new Ext.form.ComboBox({
 xtype:'combo',
fieldLabel: 'Cargo militar',
id:"idcargocivil",
editable :false,
allowBlank:false,
triggerAction:'all',
forceSelection:true,
emptyText:'Seleccione el tipo..',
hideLabel:false,
autoCreate: true,
mode: 'local',
forceSelection: true,
anchor:'93%',
store:st_DenomCargoMilitar,
displayField:'dencargomilitar',
valueField:'idcargomilitar',
hiddenName:'idcargomilitar'
});
var cb_categoria =  new Ext.form.ComboBox({
xtype:'combo',
fieldLabel: 'CategorÃ­a',
id:"categoria",
allowBlank:false,
editable :false,
triggerAction:'all',
forceSelection:true,
emptyText:'Seleccione el tipo..',
hideLabel:false,
autoCreate: true,
mode: 'local',
forceSelection: true,
anchor:'93%',
store:st_CategoriaCivil,
displayField:'dencategcivil',
valueField:'idcategcivil',
hiddenName:'idcategcivil'
});
var cb_EscalaMando =  new Ext.form.ComboBox({
xtype:'combo',
fieldLabel: 'Escala de mando',
hideLabel:false,
mode: 'local',
allowBlank:false,
editable :false,
triggerAction:'all',
forceSelection:true,
emptyText:'Seleccione visibilidad...',
id: 'visible',
anchor:'93%',
store:st_EscalaMando,
valueField:'tienemando',
hiddenName:'tienemando',
displayField:'bol'
});
var gradoMilitar =  new Ext.form.ComboBox({
xtype:'combo',
fieldLabel: 'Grado',
allowBlank:false,
editable :false,
triggerAction:'all',
forceSelection:true,
emptyText:'Seleccione ..',
hideLabel:false,
autoCreate: true,
mode: 'local',
forceSelection: true,
anchor:'93%',
store:st_GradoMilitar,
id:"grado",
displayField:'dengradomilit',
valueField:'idgradomilit',
hiddenName:'idgradomilit'
});

		var cb_Especialidad =  new Ext.form.ComboBox({
									fieldLabel: 'Especialidad',
									store:st_DenomEspecialidad,
									editable :false,
									triggerAction:'all',
									allowBlank:false,
									forceSelection:true,
									emptyText:'Seleccione el tipo..',
									hideLabel:false,
									autoCreate: true,
									mode: 'local',
									forceSelection: true,
									anchor:'93%',
									//id:"iespecialidad",
									valueField:'idespecialidad',
									hiddenName:'idespecialidad',
									displayField:'denespecialidad'
									});*/