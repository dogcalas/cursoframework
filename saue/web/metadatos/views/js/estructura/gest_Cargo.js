
/** Fichero javascript que contiene los componentes implementados en 
 * EXTJS para gestionar(Agregar,modificar, eliminar, buscar un cargo) 
 * 
 */
      /** DECLARACION DE LAS VARIABLES**/
 /*STORE PARA MOSTRAR LOS CARGOS EN EL GRID*/
 var panel,gd_MostrarCargos,fm_Cargos,fun_sel,tipo_Cargo,Gestionar,st_MostrarCargos,sm_MostrarCargo,nodoop;
GestionarCargos = function(){
			this.id;			
			var wn_FormCargos,
			col1,wn_GestionarCargo,
			menu,record;
			
			var id = this.id;
			this.titulo_W;			
			
			var Record_ES;
			
			var puestos = new GestionarPuestos();
			var medios = new GestionarMedios();
			
			var SelecTipoCargo=[['civ', 'Civil'],['mil', 'Militar']];
			
			var stTipoCargo = new Ext.data.SimpleStore({
        						fields: ['id_carg', 'cargo'],
        						data : SelecTipoCargo
    						});
			
			 st_MostrarCargos =  new Ext.data.Store({
										url: 'mostrarcargos',
										reader:new Ext.data.JsonReader({
											root:'datos',
											id:'idcargo',
											totalProperty:'cant'
											},
											[
											{name: 'idcargo'},											
											{name: 'idestructuraop'},
											{name: 'idespecialidad'},
											{name: 'denespecialidad' ,mapping:'NomEspecialidad.denespecialidad'},
											{name: 'dentipocifra' ,mapping:'NomTipocifra.dentipocifra'},											
											{name: 'ctp'},
											{name: 'ctg'},
											{name: 'orden'},
											{name: 'estado'},
											{name: 'fechaini',type:'date',dateFormat: 'Y-m-d'},
											{name: 'fechafin',type:'date',dateFormat: 'Y-m-d'},
											{name: 'tipocargo'}
											
											]
										)
                            										
						});
						
			sm_MostrarCargo = new Ext.grid.RowSelectionModel({singleSelect:true});
	 	
			var	menu =new Ext.menu.Menu({
						items:[{
								text: 'Cargo civil',
								id:'Civil',
								handler: function(){
										 tipo_Cargo = this.id;
										 AgregarCamposCivil();
										 CrearForma();
										 mostrarWin_FormCargos('Adicionar cargo civil');
									}
							},{
								text: 'Cargo militar',
								id:'Militar',
								handler: function(){
									tipo_Cargo = this.id;
									AgregarCamposMilitar();
									CrearForma();
									mostrarWin_FormCargos('Adicionar cargos militar');
									}
							}]
					});
			var bt_AdicionarCargo = new Ext.Button({
										text:'Adicionar',
										icon:perfil.dirImg+'adicionar.png',
										iconCls:'btn',
										id:"Adicionar",
										menu: menu,
										handler:function(){
												fun_sel = this.id;
												//LimpiaForm_Cargos();
												
											}									
								});
			
			var bt_ModificarCargo = new Ext.Button({
										text:'Modificar',
										icon:perfil.dirImg+'modificar.png',
										iconCls:'btn',
										id:"Modificar",
										disabled:true,										
										handler:function(){
												fun_sel= this.id;
												TipoCargo();
											}									
								});								
			
			var bt_EliminarCargo = new Ext.Button({
										text:'Eliminar',
										icon:perfil.dirImg+'eliminar.png',
										iconCls:'btn',
										id:"Eliminar",
										disabled:true,											
										handler:function(){eliminarCargos();}									
								});
			/*var bt_GestionarPuestos= new Ext.Button({
										text:'Gestionar puestos',
										icon:perfil.dirImg+'eliminar.png',
										iconCls:'btn',
										id:"Eliminar",
										disabled:true,
										handler:function(){botonclick=0;Gestionar();}									
								});//mike]*/
           /*codigo mike*/ var bt_GestionarMedios= new Ext.Button({
										text:'Gestionar medios',
										//icon:perfil.dirImg+'eliminar.png',
										iconCls:'btn',
										id:"Eliminar",
										disabled:true,
										handler:function(){botonclick=1;Gestionar();}									
								});		
								//endmike
			var bt_AyudaCargo = new Ext.Button({
										icon:perfil.dirImg+'ayuda.png',
										iconCls:'btn',
										id:"Ayuda",
										handler:function(){}									
								});	

			 gd_MostrarCargos = new Ext.grid.GridPanel({
							frame:true,
							id:'id_gd_MostrarCargos',
							iconCls:'icon-grid',
							autoExpandColumn:'expandir',
							store:st_MostrarCargos,
							sm:sm_MostrarCargo,
							loadMask:{store:st_MostrarCargos},
							viewConfig :{forceFit :true},
							columns: [
										{header: 'Especialidad', dataIndex: 'denespecialidad',id:'expandir'},
										{header: 'Cifra', width:50, dataIndex: 'dentipocifra'},
										{header: 'CTP', width:40, dataIndex: 'ctp'},
										{header: 'CTG', width:40, dataIndex: 'ctg'},
										{header: 'Orden', width:60, dataIndex: 'orden'},
										{header: 'Estado', width:40, dataIndex: 'estado'},
										{header: 'Fecha inicio', width:80, dataIndex: 'fechaini',renderer: Ext.util.Format.dateRenderer('d/m/Y')},
										{header: 'Fecha fin', width:80, dataIndex: 'fechafin',renderer: Ext.util.Format.dateRenderer('d/m/Y')}//,{header: 'Salario', width:100, dataIndex: 'fechafin'}
							],
							tbar:[bt_AdicionarCargo,bt_ModificarCargo,bt_EliminarCargo,bt_GestionarMedios,'-',bt_AyudaCargo],
							bbar: new Ext.PagingToolbar({
											pageSize: 10,
											store: st_MostrarCargos,
											displayInfo: true
											
								})
						});
			
			var st_DenomEspecialidad = new Ext.data.Store({
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
			        						 			}] 
											)
							});
			var st_CategoriaCivil = new Ext.data.Store({
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
			        						 			}] 
											)
							});
							
			var st_GrupoComplejidad = new Ext.data.Store({
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
			        						 			}] 
											)
							});
			var Datos=[['1', 'Si'],['0', 'No']];
			
			var st_EscalaMando = new Ext.data.SimpleStore({
        						fields: ['tienemando', 'bol'],
        						data : Datos
    						});
			var cb_CargoCivil = new Ext.form.ComboBox({
							xtype:'combo',
							fieldLabel: 'Denominación',
							id:"idcargocivil",
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
			
			var cb_CargoMilitar = new Ext.form.ComboBox({
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
							fieldLabel: 'Categoría',
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
			var cb_escalaSalarial = new Ext.form.ComboBox({
						 	xtype:'combo',
							fieldLabel: 'Escala salarial',
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
				});
			var nf_Salario = new Ext.form.NumberField({
							xtype:'numberfield',
							fieldLabel: 'Salario',
							maxLength:8,
							readOnly:true,
							maxLengthText:'El máximo de caracteres es 8',
							name: 'salario',
							regex:/^\d*$/,   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
							regexText:'Valor incorrecto',
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'
				});
				
			var nf_cantidad =  new Ext.form.NumberField({
									xtype:'numberfield',
									fieldLabel: 'Cantidad',
									maxLength:8,
									maxLengthText:'El máximo de caracteres es 8',
									name: 'cantidad',
									regex:/^\d*$/,   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
									regexText:'Valor incorrecto',
									allowBlank:false,
									blankText:'Este campo es requerido.',
									anchor:'93%'
				});
		var tf_CatOcup = new Ext.form.TextField({
							xtype:'textfield',
							fieldLabel: 'Categoría ocupacional', 
							name: 'denom', 
							id:'idcatocup',
							allowBlank:false,
							readOnly: true,
							anchor:'93%',						
							regex:/^(\W|\w){1,255}$/,
                            maskRe:/^(\W|\w){1,255}$/  
						});
		var cb_Reponsabilidad =  new Ext.form.ComboBox({
							xtype:'combo',
								fieldLabel: 'Reponsabilidad',
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
								id:"iespecialidad",
								valueField:'idespecialidad',
								hiddenName:'idespecialidad',
								displayField:'denespecialidad'
						});
		var df_fi = new Ext.form.DateField({
								xtype:'datefield',
								fieldLabel: 'Fecha inicio',
								readOnly :true,
								name: 'fechaini',								
								format :'d/m/Y',
								value : new Date(),
								anchor:'95%'
								
						});
		var cb_complejidad = new Ext.form.ComboBox({
								xtype:'combo',
								fieldLabel: 'Grupo de complejidad',
								store:st_GrupoComplejidad,	
								disable: true,
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
								id:"idgrupcom",
								valueField:'idgradomilit',
								hiddenName:'idgradomilit',
								displayField:'dengradomilit'
						});
		var cb_Clasifcargo = new Ext.form.ComboBox({
								xtype:'combo',
								fieldLabel: 'Clasificación de cargo',
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
								id:"fespecialidad",
								valueField:'idespecialidad',
								hiddenName:'idespecialidad',
								displayField:'denespecialidad'
						});
		var tf_fin = new Ext.form.DateField({
							xtype:'datefield',
							fieldLabel: 'Fecha fin',
							readOnly :true,
							format :'d/m/Y',
							id: 'fechafin',
							anchor:'95%'
						});

/**---------------------- DECLARACION DE LAS FUNCIONES----------------------------------**/
			
			
		
			sm_MostrarCargo.on("rowselect",function(_sm, indiceFila, record){	
   					//rec = record;
				record=record;
				Chk_BotonGestionarPuestos();
				Chk_BotonGestionarMedios();
				
   				});
   				
   				
				
				
				
				
			/**FUNCION QUE INSERTA LOS COMONENTES DE CARGO CIVIL EN CAO QUE SE SELECCIONE
			 * ADICIONAR UN CARGO CIVIL * 
			 */
	AgregarCamposCivil = function(){			
					col1 = {	columnWidth:.32,layout: 'form',
								items: [cb_CargoCivil,cb_escalaSalarial ,nf_Salario,nf_cantidad]
							};
					 col2 = { 	columnWidth:.32,layout: 'form',
								items: [tf_CatOcup,cb_Reponsabilidad,df_fi]
							}; 

					 col3 = {	columnWidth:.32,layout: 'form',
								items: [cb_complejidad,cb_Clasifcargo,tf_fin]
							};						
					}
				/*if(col1.items.length == 3){
							col1.items.push(cb_CargoCivil);
							col2.items.push(cb_categoria);
					}
					else{
							col1.items.remove(cb_EscalaMando);
							col2.items.remove(gradoMilitar);
							col3.items.remove(cb_CargoMilitar);
							col1.items.remove(cb_CargoCivil);
							col2.items.remove(cb_categoria);
							AgregarCamposCivil();
					}*/
					
			/**FUNCION QUE INSERTA LOS COMONENTES DE CARGO MILITAR EN CASO QUE SE SELECCIONE
			 * ADICIONAR UN CARGO MILITAR * 
			 */		
			var nf_Estado =  new Ext.form.NumberField({
								xtype:'numberfield',
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
			var cb_Especialidad = new Ext.form.ComboBox({
							 	xtype:'combo',
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
						});
			var cb_Cifra = new Ext.form.ComboBox({
							xtype:'combo',							
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
			var nf_CTP = new Ext.form.NumberField({
							xtype:'numberfield',							
							fieldLabel: 'CTP',
							maxLength:3,
							maxLengthText:'El m&aacute;ximo de caracteres es 3',
							name: 'ctp',
							regex:/^\d*$/,   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
							regexText:'Valor incorrecto',
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'
						});
			var nf_salario = new Ext.form.NumberField({
							xtype:'numberfield',
							fieldLabel: 'Salario',
							maxLength:8,
							maxLengthText:'El m&aacute;ximo de caracteres es 8',
							name: 'salario',
							regex:/^\d*$/,   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
							regexText:'Valor incorrecto',
							allowBlank:false,
							blankText:'Este campo es requerido.',
							anchor:'93%'
						});
			var df_inicio = new Ext.form.DateField({
								xtype:'datefield',
								fieldLabel: 'Fecha inicio',
								readOnly :true,
								name: 'fechaini',								
								format :'d/m/Y',
								value : new Date(),
								anchor:'95%'								
						});
			var nf_CTG = new Ext.form.NumberField({
								xtype:'numberfield',
								fieldLabel: 'CTG',
								maxLength:3,
								maxLengthText:'El m&aacute;ximo de caracteres es 3',
								id: 'ctg',
								name:'ctg',
								regex:/^\d*$/,               // /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
								regexText:'Valor incorrecto',
								allowBlank:false,
								blankText:'Este campo es requerido.',
								anchor:'93%'
						});
			var  nf_Orden = new Ext.form.NumberField({
								xtype:'numberfield',
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
			var nf_fin = new Ext.form.DateField({
							xtype:'datefield',
							fieldLabel: 'Fecha fin',
							readOnly :true,
							format :'d/m/Y',
							id: 'fechafin',
							anchor:'95%'
						});
						
			AgregarCamposMilitar = function(){		
			
					col1 = {	columnWidth:.32,layout: 'form',
								items: [nf_Estado,cb_Especialidad,cb_Cifra,cb_EscalaMando]
							};
					col2 = {	columnWidth:.32,layout: 'form',
								items: [nf_CTP,nf_salario,df_inicio,gradoMilitar]
							};
					col3 = {	columnWidth:.32,layout: 'form',
								items: [nf_CTG,nf_Orden,,cb_CargoMilitar,nf_fin]
							};
				};
				
				/*if(col1.items.length == 3){
						col1.items.push(cb_EscalaMando);
						col2.items.push(gradoMilitar);
						col3.items.push(cb_CargoMilitar);
				}
				else {
					col1.items.remove(cb_EscalaMando);
					col2.items.remove(gradoMilitar);
					col3.items.remove(cb_CargoMilitar);
					col1.items.remove(cb_CargoCivil);
					col2.items.remove(cb_categoria);							
					AgregarCamposMilitar();
				}
				//fm_Cargos.doLayout();*/
			QuitarCampos = function(){
						if( col1.items.length > 3){
								col1.items.remove(cb_EscalaMando);
								col2.items.remove(gradoMilitar);
								col3.items.remove(cb_CargoMilitar);
								col1.items.remove(cb_CargoCivil);
								col2.items.remove(cb_categoria);
						}
				};
	function OnDenominacionSelect(c,r,i){			
		     st_GrupoComplejidad.baseParams ={idcargocivil:r.data.idcargociv};
			 st_GrupoComplejidad.load();
			 recor = st_GrupoComplejidad.getAt(0);
			 selectGrupoComple.setValue(valor); 
		};
	function TipoCargo(){
				  tipocargo = sm_MostrarCargo.getSelected().get("tipocargo");
				  idcargo = sm_MostrarCargo.getSelected().get("idcargo");
				  if(tipocargo == "civil"){
					 AgregarCamposCivil();
					 CrearRecordCivil();					 
					CrearForma();
					fm_Cargos.getForm().load({url:'mostrardatoscargo',params:{idcargo:idcargo,tipocargo:tipocargo},waitMsg:'Cargando datos'});
					mostrarWin_FormCargos();					
				}
				else{
					AgregarCamposMilitar();
					CrearRecordMilitar();
					CrearForma();
					fm_Cargos.getForm().load({url:'mostrardatoscargo',params:{idcargo:idcargo,tipocargo:tipocargo},waitMsg:'Cargando datos'});
					mostrarWin_FormCargos();
					
				}
			}
			function CrearRecordMilitar(){
				Record_ES = Ext.data.Record.create([
							{name: 'idcargo'},											
							{name: 'idestructuraop'},
							{name: 'idespecialidad'},
							{name: 'denespecialidad' ,mapping:'NomEspecialidad.denespecialidad'},
							{name: 'dentipocifra' ,mapping:'NomTipocifra.dentipocifra'},
							{name:'idtipocifra'},
							{name: 'ctp'},
							{name: 'ctg'},
							{name: 'orden'},
							{name: 'estado'},
							{name: 'fechaini'},
							{name: 'fechafin'},
							{name: 'tipocargo'},
							{name: 'salario',mapping:'DatCargomtar.salario'},
							{name: 'tienemando',mapping:'DatCargomtar.escadmando'},											
							{name: 'idgradomilit',mapping:'DatCargomtar.idgradomilit'},
							{name: 'idcargomilitar',mapping:'DatCargomtar.idcargomilitar'}							
							]);
				
			}
			function CrearRecordCivil(){
				Record_ES = Ext.data.Record.create([
							{name: 'idcargo'},											
							{name: 'idestructuraop'},
							{name: 'idespecialidad'},
							{name: 'denespecialidad' ,mapping:'NomEspecialidad.denespecialidad'},
							{name: 'dentipocifra' ,mapping:'NomTipocifra.dentipocifra'},
							{name:'idtipocifra'},
							{name: 'ctp'},
							{name: 'ctg'},
							{name: 'orden'},
							{name: 'estado'},
							{name: 'fechaini'},
							{name: 'fechafin'},
							{name: 'tipocargo'},
							{name: 'salario',mapping:'DatCargocivil.salario'},
							{name: 'idcargociv',mapping:'DatCargocivil.idcargociv'},
							{name: 'idcategcivil',mapping:'DatCargocivil.idcategcivil'}
							]);
				
				
			}
			
			/**FUNCION PUBLICA QUE MUSTRA EL GRID CON LOS CARGOS
			 * Y A TRAVES DE ELLA SE ACCEDE AL CASO DE USO GESTIONAR CARGO* 
			 */
			this.mostrarWin_GestCargo = function (){
				id = this.id;
				var tit = this.titulo_W;
				st_MostrarCargos.baseParams = {idop:id }
				st_MostrarCargos.load({params:{start:0,limit:10}})
					if(!wn_GestionarCargo){						
							wn_GestionarCargo = new Ext.Window({
												title:'Definir cargos',
												layout:'fit',
												width:600,
												modal:true,
												height:300,
												closeAction:'hide',
												items:gd_MostrarCargos
											});
					
					}
					wn_GestionarCargo.setTitle(tit);
				    wn_GestionarCargo.show(this);
				}
				
				this.mostrarGrid_GestCargo=function (){
				    
					id = this.id;
				    st_MostrarCargos.baseParams = {idop:id }
				   st_MostrarCargos.load({params:{start:0,limit:10}})
				   
				   
				   
				
				}
				
				Gestionar = function (){
				
			
			/*if(!botonclick)
			{
				//Ext.MessageBox.show({title:'Alerta',msg: 'En construccion'});	  
			
				puestos.title_WinGestionarPuestos = "Gestionar puestos de la especialidad: " + DameSeleccionadoCargos("denespecialidad");
	        	puestos.id = DameSeleccionadoCargos("idcargo");
	        	puestos.mostrarWin_GestPuestos();
			}
					
			else*/
		   //if( botonclick)
		  // {//Ext.MessageBox.show({title:'Alerta',msg: 'En construccion'});	
		   		//medios.id = DameSeleccionadoCargos("idcargo");
				medios.id=nodoop.id
				medios.title_WinGestionarTecnica = "Gestionar medios de la especialidad: " + nodoop.text//DameSeleccionadoCargos("denespecialidad");
	        	medios.mostrarWin_GestTecnicas();
		  // }
		   
			}
			
			/*LimpiaForm_Cargos = function (){
					fm_Cargos.findById('nombre').setValue("");
					fm_Cargos.findById('fechaini').setValue( new Date() );
					fm_Cargos.findById('fechafin').setValue("");
				};*/
			/*FUNCION PRIVADA DE LA CLASE QUE MUESTRA UNA VENTANA LA CUAL CONTIENE UN*
			 * FORMULARIO CON LOS COMPONENTES PARA AGREGAR Y MODIFICAR UN CARGO* 
			 */
			mostrarWin_FormCargos = function (titulo){
			if(!titulo)
			  titulo='Confeccionar cargos'
						wn_FormCargos = new Ext.Window({
											title:titulo,
											layout:'fit',
											width:600,
											height:300,
											modal:true,
											items:fm_Cargos,											
											buttons:
											[{
												text:'Cancelar',
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												handler:function(){
															fm_Cargos.getForm().reset();
															wn_FormCargos.destroy();
												}
											},{
												text:'Aceptar',
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												handler:function(){
												GestionarFuncionCargos();
												}						
											}]
								});
						wn_FormCargos.show(this);
					};
			/**FUNCION PRIVADA DE LA CLASE LA CUAL CONSTRULLE UN*
			 * FORMULARIO CON LOS COMPONENTES PARA AGREGAR Y MODIFICAR UN CARGO* 
			 */
			CrearForma = function(){
					fm_Cargos = new Ext.FormPanel({
									labelAlign: 'top',
									frame:true,
									reader: new Ext.data.JsonReader({
										root:"datos",
										successProperty:"success"
									},Record_ES),
									border:'false',										
									items:[{
										layout:'column',
										style:'margin:5 0 0 10',
										items:[col1,col2,col3]
									}]
								});	
				}
			/**FUNCION PRIVADA DE LA CLASE LA CUAL GESTIONA LAS URL Y LOS PARAMETROS A DONDE SE DEBEN
			 * ENVIAR LOS DATOS DEL FORMULARIO 
			 */	
			GestionarFuncionCargos =  function(){
			          if(nodoop)id= nodoop.id;
					if(fun_sel =="Modificar"){					
						if(tipocargo =='civil')
							sbmt_EnviarfmCargos('modificarcargoscivil',{idcargo:sm_MostrarCargo.getSelected().get("idcargo")});
						else
							sbmt_EnviarfmCargos('modificarcargomtar',{idcargo: sm_MostrarCargo.getSelected().get("idcargo") });
					}
					else
					if(fun_sel =="Adicionar"){
					
						if(tipo_Cargo =='Civil'){
						
							sbmt_EnviarfmCargos('insertarcargocivil',{idop:id});
						} 
						else{
							sbmt_EnviarfmCargos('insertarcargomtar',{idop:id}); //,{idpadre:DameSeleccionado("idnomeav"),SUFIX:id_TipoEst}
						}
					}
				}
			/**FUNCION QUE REALIZA EL ENVIO DEL FORMULARIO AL SERVIDOR* 
			 */
			sbmt_EnviarfmCargos = function(url,params){
					if (fm_Cargos.getForm().isValid())
						{
							fm_Cargos.getForm().submit({
									url:url,
									params:params,	
									failure: function(form, action){
											mostrarMensaje(action.result.codMsg,action.result.mensaje);
											if(action.result.codMsg != 3){
												//st_MostrarCargos.reload();
												fm_Cargos.getForm().reset();
												wn_FormCargos.destroy();
											 }
									}
								,waitMsg:'Enviando los datos...'});
						}
					else{ 
					mostrarMensaje(3,'Por favor verifique nuevamente que hay campo(s) con valor(es) incorrecto(s).');
					}						
				};
		Chk_BotonGestionarPuestos= function()
			{
		 	if(sm_MostrarCargo.hasSelection()){
		 		bt_EliminarCargo.enable();
   				bt_ModificarCargo.enable();
   				//bt_GestionarPuestos.enable();
   				bt_GestionarMedios.enable();
		 	}
		 	else{
		 		bt_EliminarCargo.disable();
   				bt_ModificarCargo.disable();
   				//bt_GestionarPuestos.disable();
   				bt_GestionarMedios.disable();
			}
			//mike
			
			Chk_BotonGestionarMedios= function()
			{
		 	if(sm_MostrarCargo.hasSelection()){
		 		bt_EliminarCargo.enable();
   				bt_ModificarCargo.enable();
   				//bt_GestionarPuestos.enable();
   				bt_GestionarMedios.enable();
		 	}
		 	else{
		 		bt_EliminarCargo.disable();
   				bt_ModificarCargo.disable();
   				//bt_GestionarPuestos.disable();
   				bt_GestionarMedios.disable();
			}
			//endmike
		}
			
			/**FUNCION QUE LLENA LOS CAMPOS DEL FORMULARIO CON VALORES EN CASO
			 * DE SELECCIONAR MODIFICAR* 
			 */	
			LlenarCamposForma = function(){
					fm_Cargos.findById('estado').setValue(DameSeleccionadoCargos("estado"));
					fm_Cargos.findById('especialidad').setValue(DameSeleccionadoCargos("denespecialidad"));
					fm_Cargos.findById('ctp').setValue(DameSeleccionadoCargos("ctp"));
					fm_Cargos.findById('tipocifra').setValue(DameSeleccionadoCargos("dentipocifra"));
					fm_Cargos.findById('fechaini').setValue(DameSeleccionadoCargos("fechaini"));
					fm_Cargos.findById('orden').setValue(DameSeleccionadoCargos("orden"));
					fm_Cargos.findById('ctg').setValue(DameSeleccionadoCargos("ctg"));
					fm_Cargos.findById('fechafin').setValue(DameSeleccionadoCargos("fechafin"));
					fm_Cargos.findById('salario').setValue(DameSeleccionadoCargos("sal"));
				};
			DameSeleccionadoCargos= function(idp){
				var params ="";
					if(sm_MostrarCargo.hasSelection())
						{
							params = sm_MostrarCargo.getSelected().get(idp);
						}
						
					return params;
				}
			eliminarCargos = function(){
				
					if(!sm_MostrarCargo.getSelected())
						mostrarMensaje(1,'Debe seleccionar el  que desea eliminar.');
					else{
						mostrarMensaje(2,'¿Está seguro que desea eliminar al cargo ?',elimina)
					}
					function elimina(btnPresionado){
							if (btnPresionado == 'ok'){
									Ext.Ajax.request({
											url: 'eliminarcargo',
											method:'POST',
											params:{idcargo:sm_MostrarCargo.getSelected().data.idcargo},
											callback: function (options,success,response){
																responseData = Ext.decode(response.responseText);
																mostrarMensaje(responseData.codMsg,responseData.mensaje);
																st_MostrarCargos.reload();
																sm_MostrarCargo.clearSelections();
																Chk_BotonGestionarPuestos();
																Chk_BotonGestionarMedios();
											}
											,waitMsg:'Eliminando los datos...'});
							}
			
					}
				}
				
			
			
			/*VENTANA QUE MUESTRA LOIS MENSAJES DEL SERVIDOR
			 * 
			 */
			/*mostrarMensaje = function(tipo, msg, fn){
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
				}*/
			
			
	}
}

