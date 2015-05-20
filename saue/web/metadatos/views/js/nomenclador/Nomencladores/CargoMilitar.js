

Ext.QuickTips.init();	    
NomencladorCargoMilitar = function(){

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
		cm_CargoM = [
		            { dataIndex: 'denom', sortable: true,header:perfil.etiquetas.lbdenominacion,id:'expandir',width:180},
		            { dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura,width:70},
		            { dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden,width:50},		           
		            { dataIndex: 'espec', sortable: true,header:perfil.etiquetas.lbespec,width:70},
		            { dataIndex: 'pmilitar', sortable: true,header: perfil.etiquetas.lbPreparacionmilitar}	,
		            { dataIndex: 'fini', sortable: true,header:perfil.etiquetas.lbfechaini,width:70,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            { dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,width:70,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		        	];
 		rc_CargoM = [
		           {name: 'denom', mapping:'dencargomilitar'},		         
		           {name: 'abrev', mapping :'abrevcargomilitar'},
		           {name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},	
		           {name: 'orden', mapping : 'orden'},
		           {name: 'espec', mapping :'Especialidad'},
		           {name: 'idespecialidad', mapping :'idespecialidad'},
		           {name: 'pmilitar', mapping : 'NomPrepmilitar.denprepmilitar'},
		           {name: 'idprepmilitar', mapping : 'idprepmilitar'},
		           {name: 'idcargomilitar', mapping : 'idcargomilitar'}
		          ];
		var col1_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: perfil.etiquetas.lbdenominacion, //MODIFICLABLE
							name: 'denom', 				//MODIFICLABLE
							allowBlank:false,
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%'//,							regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/  
						},{
							fieldLabel:perfil.etiquetas.lbabreviatura, //MODIFICLABLE
							name: 'abrev', 	
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,       //MODIFICLABLE   
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
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
		var p_militar = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrarpmilitar',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idprepmilitar"    
			    										},[{
			        										name: 'idprepmilitar'},{
			        						 				name: 'denprepmilitar'}] 
											)
							}
    						 );
			
		var col2_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'combo',
						items:[{
							fieldLabel: perfil.etiquetas.lbPreparacionmilitar,
							id:"pmilitar",
							allowBlank:false,
							editable :false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,				
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:p_militar,
							displayField:'denprepmilitar',
							valueField:'idprepmilitar',
							hiddenName:'idprepmilitar'
						},{
							xtype:'textfield',
							fieldLabel: perfil.etiquetas.lbespec,							
							id:"espec",
							name:'denespecialidad',
							editable :false,
							readOnly:true,
							allowBlank:true,
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
		
 		item_GradoM = [col1_FormNomenclador,col2_FormNomenclador,col3_FormNomenclador]; 		
 		this.cargarStore=function()
		{
		 p_militar.load();
		}
		this.cm = cm_CargoM;
 		this.item = item_GradoM;
 		this.rc = rc_CargoM;
 		this.referencia='cargomtar';
	
};