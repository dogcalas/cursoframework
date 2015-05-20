

function NomencladorCargoCivil(){

var abrirtreeEspecialidadCM,WintreeEspecialidadCM;


//--------------------Arbol de especialidad----------------
	var loaderEspecialidadCM=new Ext.tree.TreeLoader({
							dataUrl:'hijoespecialidad'
							
							
					});
		var treeEspecialidadCM = new Ext.tree.TreePanel({        
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
	        			loader: loaderEspecialidadCM,
                        listeners:{
						      click:function(n)
							  {							   
								var comp=Ext.getCmp('espec');
								var tf_idesp=Ext.getCmp('idtxtespecialidad');
							    comp.setValue(n.text);
								tf_idesp.setValue(n.id);
								idesp=n.id;
								WintreeEspecialidadCM.hide();
							  
							  }
						
						}						
	        			
			});
			
	    var rootEspecialidad = new Ext.tree.AsyncTreeNode({
       					text: 'Especialidad',
        				draggable:false,
        				id:'idespecialidad'
					});
				
		treeEspecialidadCM.setRootNode(rootEspecialidad);
		
		
		treeEspecialidadCM.show();
		
		
		//rootEspecialidad.expand();

	
		function WinFntreeEspecialidadCM(){
	         
	   
		   if (!WintreeEspecialidadCM) {
		   	
                WintreeEspecialidadCM = new Ext.Window({
						                    title: 'Especialidad',
						                    layout:'fit',
						                    items: [treeEspecialidadCM],                                        
						                    width: 300,
											modal:true,
						                    height: 400,
						                    closeAction: 'hide',
						                    plain: true
						                
						                });
            }
            WintreeEspecialidadCM.show();
	
	}	
			
        abrirtreeEspecialidadCM=function(){
		 
		WinFntreeEspecialidadCM();
		
		}
	Ext.QuickTips.init();	
		cm_CargoC = [
		            { dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:82},
		            { dataIndex: 'codigo', sortable: true,header: perfil.etiquetas.lbcod,width:68},
		          //  { dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden,width:68},
		            { dataIndex: 'espec', sortable: true,header: perfil.etiquetas.lbespec,width:68},
		            { dataIndex: 'calific', sortable: true,header:  perfil.etiquetas.lbCalificador,width:41},		           
		            { dataIndex: 'nutilizacion', sortable: true,header:  perfil.etiquetas.lbNivelutilizacion,width:69},
		            { dataIndex: 'catocupacional', sortable: true,header: perfil.etiquetas.lbCategoriaocupacional ,width:115},
		            { dataIndex: 'grupcomplejidad', sortable: true,header:  perfil.etiquetas.lbGrupodecomplejidad,width:81},
		            { dataIndex: 'descrip', sortable: true,header: perfil.etiquetas.lbtareasprincipales ,width:115},
		            { dataIndex: 'requisito', sortable: true,header:  perfil.etiquetas.lbrequisitosConocimiento,width:81},
		            { dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		            
		        	];
 		rc_CargoC = [
		           {name: 'denom', mapping:'dencargociv'},
		           {name: 'idcargociv', mapping : 'idcargociv'},
		           {name: 'nutilizacion', mapping :'NomNivelUtilizacion.denominacion'},
		           {name: 'idnivelutilizacion', mapping :'idnivelutilizacion'},
				   {name: 'abrev', mapping :'abrevcargociv'},                   				   
		           {name: 'espec', mapping :'Especialidad'},
		           {name: 'idespecialidad', mapping :'idespecialidad'},		           
		           {name: 'codigo', mapping : 'codigo'},
		           {name: 'grupcomplejidad', mapping :'NomGrupocomple.denominacion'},
		           {name: 'idgrupocomplejidad', mapping :'idgrupocomplejidad'},		           
		           {name: 'descrip', mapping :'descripcion'},
		           {name: 'requisito', mapping : 'requisitos'},
		           {name: 'idcategocup', mapping : 'NomCategocup.idcategocup'},
		           {name: 'catocupacional', mapping : 'NomCategocup.dencategocup'},		          		           
		           {name: 'calific', mapping : 'NomCalificadorCargo.denominacion'},
		           {name: 'idcalificador', mapping : 'idcalificador'},
		           {name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'}
		           //{name: 'orden'}
		          ]	;
		var st_nivelutilizacion = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrarnivelutilizacion',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idnivelutilizacion"    
			    										},[{
			        										name: 'idnivelutilizacion'
			        						 			},{
			        						 				name: 'denominacion'
			        						 			}] 
											)
							});

		var st_grupocomplejidad = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrargrupocomplejidad',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idgrupocomplejidad"    
			    										},[{
			        										name: 'idgrupocomplejidad'},{
			        						 				name: 'denominacion'
			        						 			}] 
											)
							});
		var st_calificador = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrarcalificador',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idcalificador"    
			    										},[{
			        										name: 'idcalificador'				        									
			        									},{
			        						 				name: 'denominacion'}] 
											)
							});
							
		var st_catocup = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrarcatgriaocpnal',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idcategocup"    
			    										},[{
			        										name: 'idcategocup'	},{
			        						 				name: 'dencategocup'}] 
											)
							});
				
							
		var col1_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: perfil.etiquetas.lbdenominacion, 
							name: 'denom', 				//MODIFICLABLE
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
						},{
							fieldLabel: perfil.etiquetas.lbcod, //MODIFICLABLE
							name: 'codigo', 			   //MODIFICLABLE
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%'
						},{
							xtype:'combo',
							fieldLabel: perfil.etiquetas.lbCalificador,
							allowBlank:false,
							id:"calific",
							editable :false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_calificador,
							displayField:'denominacion',
							valueField:'idcalificador',
							hiddenName:'idcalificador',
							name:'denominacion'							
							
						}/*,{
						    xtype:'textfield',		
							fieldLabel: perfil.etiquetas.lborden,
							name: 'orden', 			   //MODIFICLABLE
							regex:/^\d{1,8}$/,
                            maskRe:/^\d{1,8}$/,
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%'						
							
						}*/]
					};
		
		var col2_FormNomenclador = {
						columnWidth:.33,								
								layout: 'form',								
								defaultType:'datefield',								
								items: [{								
										fieldLabel: perfil.etiquetas.lbfechaini,
										readOnly :true,
										name: 'fini',
										id: 'idfini',
										format :'d/m/Y',
										value : new Date(),
										anchor:'90%'
									},{
										fieldLabel: perfil.etiquetas.lbfechafin,
										readOnly :true,
										id: 'idffin',
										name: 'ffin',
										format :'d/m/Y',
										anchor:'90%'							
														
							},{
							xtype:'textfield',
							fieldLabel: perfil.etiquetas.lbespec,							
							id:"espec",
							name:'denespecialidad',
							editable :false,
							readOnly:true,
							allowBlank:true,
							anchor:'93%',
							listeners:{
							            focus:function(){
										
										abrirtreeEspecialidadCM();
										}
							
							}
						},
						{
						    xtype:'textfield',		
							fieldLabel: 'Abreviatura',
							name: 'abrev', 		   //MODIFICLABLE							
							allowBlank:false,							
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%'						
							
						}]
		};
		
		var col3_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'combo',
						items:[{
							fieldLabel:perfil.etiquetas.lbCategoriaocupacional,
							allowBlank:false,
							id:"cat",
							editable :false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_catocup,
							displayField:'dencategocup',
							valueField:'idcategocup',
							hiddenName:'idcategocup',
							name:'catocupacional'
						},{
							fieldLabel: perfil.etiquetas.lbGrupodecomplejidad,
							id:"idgcomplejidad",
							editable :false,
							allowBlank:false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_grupocomplejidad,
							displayField:'denominacion',
							valueField:'idgrupocomplejidad',
							hiddenName:'idgrupocomplejidad',
							name:'grupocomplejidad'
						},{
							fieldLabel: perfil.etiquetas.lbNivelutilizacion,
							id:"idnutilizacion",
							editable :false,
							allowBlank:false,
							triggerAction:'all',
							forceSelection:true,
							listWidth:400,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_nivelutilizacion,
							displayField:'denominacion',
							valueField:'idnivelutilizacion',							
							hiddenName:'idnivelutilizacion',
							name:'nivelutilizacion'
							},
						{
						xtype:'textfield',
						fieldLabel: 'Id especialidad',
						hideLabel:true,
						hidden:true,
						id:'idtxtespecialidad',
						name: 'idespecialidad'
						}]
		};
		
	var	textarea_1 = {
		    	columnWidth:.5,
				layout: 'form',
				defaultType:'textarea',
				items:[{
			            fieldLabel: perfil.etiquetas.lbtareasprincipales,
						name: 'descrip', 			   
						regex:/^(\W|\w){1,}$/,
						maskRe:/^(\W|\w){1,}$/,
						allowBlank:false,
						regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
						//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
						invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
						anchor:'93%'
				     }]
			  };
			  
	var textarea_2	 = {
		    	columnWidth:.5,
				layout: 'form',
				defaultType:'textarea',
				items:[{	     
			            fieldLabel: perfil.etiquetas.lbrequisitosConocimiento, 
						name: 'requisito', 			   
						regex:/^\w(\s|\w){1,}$/,
						maskRe:/^\w(\s|\w){1,}$/,
						allowBlank:false,
						regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
						//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
						invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
						anchor:'93%'
				    }]
        	};
        
       
		item_CargoM = [col1_FormNomenclador,col2_FormNomenclador,col3_FormNomenclador,textarea_1,textarea_2];
 		
		this.cargarStore=function()
		{
		st_nivelutilizacion.load();
		st_grupocomplejidad.load();
		st_calificador.load();
		st_catocup.load();
		}
		
		
		this.textarea_1 = textarea_1;
		this.textarea_2 = textarea_2;
 		this.cm = cm_CargoC;
 		this.item = item_CargoM;
 		this.rc = rc_CargoC;
 		this.referencia='cargocivil';
}; 