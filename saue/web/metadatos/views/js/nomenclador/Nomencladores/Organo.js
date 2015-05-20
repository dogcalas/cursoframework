
function NomencladorOrgano(){
	Ext.QuickTips.init();
	 var abrirtreeEspecialidadCM,WintreeEspecialidadCM;

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
                        listeners:{click:function(n){							   
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
 		var cm_Organo = [
 		 			{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir'},		           
		            { dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},		           
		            { dataIndex: 'abrev', sortable: true,header:perfil.etiquetas.lbabreviatura,width:80},		            		            		            		
            		{ dataIndex: 'espec', sortable: true,header:perfil.etiquetas.lbespec,width:80},
            		{ dataIndex: 'est', sortable: true,header:'Nivel estructural' ,width:80},
            		{ dataIndex: 'nivel', sortable: true,header: perfil.etiquetas.lbnivelstr,width:100},
            	    { dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
	           		{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		        		];
		        	
		var rc_Organo = [
		           		{name: 'denom', mapping:'denorgano'},
		           		{name: 'abrev', mapping :'abrevorgano'},
		           		{name: 'idorgano', mapping :'idorgano'},
		           		{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
						{name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},
                        {name: 'idnomeav', mapping :'idnomeav'},	
                        {name: 'est', mapping :'NomNomencladoreavestruc.nombre'},		           		
		           		{name: 'idespecialidad', mapping :'idespecialidad'},
		           		{name: 'nivel', mapping :'NomNivelestr.abrevnivelestr'},
						{name: 'idnivelestr', mapping :'NomNivelestr.idnivelestr'},
						{name: 'espec', mapping :'Especialidad'},
						{name: 'idespecialidad', mapping :'idespecialidad'},
		           		{name: 'orden'}
		          		];
		          		
/*	 var datos= [['op','Internas'],['','Externas']];
	
	var stTipoEstructura = new Ext.data.SimpleStore({
	                  
		fields:['id','est']
	});
	stTipoEstructura.loadData(datos);*/
    	
  	var st_Estructura = new Ext.data.Store({
			    							autoLoad: false,			    							
			    							url: 'mostrartiposeav',
			    							reader: new Ext.data.JsonReader(
			    										{
				       										totalProperty: "cant",
				        									root: "datos",
				        									id: "idnomeav"    
			    										},[
			    										    { name: 'idnomeav'},
									      					{ name: 'nombre'}
									      					
									      				  ] 
									      			)
									      });
    	

		var st_DenomNiveles = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrarnvelestr',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idnivelestr"    
			    										},[{
			        										name: 'idnivelestr'
			        						 			},{
			        						 				name: 'dennivelestr'
			        						 			}] 
											)
							});
		var col1_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: perfil.etiquetas.lbdenominacion,
							name: 'denom',
							allowBlank:false,							
							anchor:'93%',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto
							},{
							fieldLabel: perfil.etiquetas.lbabreviatura,
							name: 'abrev',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							allowBlank:false,
							anchor:'93%'
						},{
							fieldLabel: perfil.etiquetas.lborden,
							name: 'orden',
							regex:/^\d{1,8}$/,
							maskRe:/^\d{1,8}$/,
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
							anchor:'93%'
						}]
					};
	
	
		
		
		var col2_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'combo',
						items:[{
							//fieldLabel:perfil.etiquetas.lbnivelstr,
							fieldLabel:perfil.etiquetas.lbestr,
							id:"nivel",
							editable :false,
							allowBlank:false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							anchor:'93%',
							store:st_DenomNiveles,
							displayField:'dennivelestr',
							valueField:'idnivelestr',
							hiddenName:'idnivelestr'
						},{ 
						    fieldLabel:'Nivel estructural' ,
							id:"idest",
							editable :false,
							allowBlank:false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							anchor:'93%',
							store:st_Estructura,
							displayField:'nombre',
							valueField:'idnomeav',
							hiddenName:'idnomeav',
							name:'est'
 	
                      },{
							xtype:'textfield',
							fieldLabel: perfil.etiquetas.lbespec,							
							id:"espec",
							name:'denespecialidad',
							editable :false,
							readOnly:true,
							allowBlank:false,
							anchor:'93%',
							listeners:{	focus:function(){abrirtreeEspecialidadCM();}							
							}
						},{
							xtype:'textfield',
							fieldLabel: 'Id especialidad',
							hideLabel:true,
							hidden:true,
							id:'idtxtespecialidad',
							name: 'idespecialidad'
						}]
		};
	
		
		var col3_FormNomenclador = {
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
							}]
					};
	    
	  	

				   
 		var item_Organo = [col1_FormNomenclador,col2_FormNomenclador,col3_FormNomenclador];
 		
		 /**
		  * @public
		  */
 		this.cargarStore=function()
		{
		  st_Estructura.load();
		  st_DenomNiveles.load();
		  
		}
 		this.cm = cm_Organo;
 		this.item = item_Organo;
 		this.rc = rc_Organo;
 		/**@type{String} */
 		this.referencia='organos';
 		
 			
 		/*on('select',function(c,r,i){
	   				id_TipoEst =r.get("id_est");
	   				//alert(id_TipoEst);
	   				modoSeleccionTabla.clearSelections();
	   				store01.baseParams ={SUFIX:id_TipoEst};
	   				store01.load({params:{start:0, limit:10}});
 		var a = Ext.getCmp('testruc');
 		a= a.getValue()
          function TomarValor(){
 		    combo = Ext.getCmp(testruc);
 		 
 		 	if(combo.getValue()== "Internas" ){
 		 	
 		 	return "Internas" ;
 		 	}
 		 	else{
 		 
 		 	return st_Estructura.baseParams = {SUFIX:"externas"};
 		 	}
 		 	 		    
 		}*/
 		/*function Fecha()
 		{
 		 var hoy = new Date();
 		 alert (hoy.getDate()+'/'+hoy.getMonth()+'/'+hoy.getFullYear());
 		}*/
 	
};