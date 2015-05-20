  
function NomencladorGradoMilitar(){
	Ext.QuickTips.init();
	
	var cm_GradoM = [
		            { dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:75},
		            { dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura,width:65},
		            { dataIndex: 'categ', sortable: true,header: perfil.etiquetas.lbSubcategoria,width:71},
		            { dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden,width:36},		            
		            { dataIndex: 'anterior', sortable: true,header: perfil.etiquetas.lbAnterior,renderer:verificarNull,width:47},
		            { dataIndex: 'sucesor', sortable: true,header: perfil.etiquetas.lbSucesor,renderer:verificarNull,width:47},
		            { dataIndex: 'marina', sortable: true,header:perfil.etiquetas.lbEsmarina,renderer:change,width:55},
		            { dataIndex: 'homologo', sortable: true,header:perfil.etiquetas.lbHomologoentierra,renderer:changeValor,width:97},
		            { dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,width:65,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            { dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,width:62,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		        	];
		        	
 	var rc_GradoM = [
		           {name: 'denom', mapping:'dengradomilit'},
		           {name: 'abrev', mapping :'abrevgradomilit'},
		           {name: 'orden', mapping : 'orden'},
		           {name: 'categ', mapping : 'NomGsubcateg.densubcateg'},
		           {name: 'idgsubcateg', mapping : 'NomGsubcateg.idgsubcateg'},
		           {name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'anterior', mapping :'NomGradomilit'},
		           {name: 'sucesor', mapping :'Sucesor'},
		           {name: 'valanterior', mapping :'anterior'},
		           {name: 'valsucesor', mapping :'sucesor'},
		    
		           {name: 'marina', mapping : 'esmarina'},
		           {name: 'idgradomilit', mapping : 'idgradomilit'},
		           {name: 'homologo', mapping : 'homologoterr'}
		          ];
		          
		          var subcat = new Ext.data.Store({
											autoLoad: false,
			    							url: 'mostrarsbcategoria',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idgsubcateg"    
			    										},[{
			        										name: 'idgsubcateg'},{
			        						 				name: 'densubcateg'}] 
											)
							})	 
					
		          
	var col1_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: perfil.etiquetas.lbdenominacion, //MODIFICLABLE
							name: 'denom', 				//MODIFICLABLE
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
						},{
							fieldLabel: perfil.etiquetas.lbabreviatura, //MODIFICLABLE
							name: 'abrev', 			   //MODIFICLABLE
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
						},{
							fieldLabel: perfil.etiquetas.lborden,
							name: 'orden',							
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
							anchor:'93%',
							regex:/^\d{1,8}$/,
                            maskRe:/^\d{1,8}$/
						},{
							xtype:'combo', 
							fieldLabel: perfil.etiquetas.lbSubcategoria,
							id:"categ",
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
							store:subcat,
							displayField:'densubcateg',
							valueField:'idgsubcateg',
							hiddenName:'idgsubcateg'
						}
						]
					};
					
		function verificarNull(valor)
		{
		 
		  if(valor)
		  return valor.dengradomilit;
		  return '';
		}
		
		function change(val){
			if(val == "0"){
				return  perfil.etiquetas.lbno;     
			}else if(val == "1"){
				 return  perfil.etiquetas.lbsi;
			}
			return val;
		};
    	function changeValor(val){
        if(val == "0"){
            return  perfil.etiquetas.lbno;     
        }else if(val == "1"){
             return  perfil.etiquetas.lbsi;
        }
        return val;
    };
		/**
		 * SOLO MODIFICABLES LOS ELEMENTOS DENTRO DE LA COLUMNA
		 */	       var datos = [['1','Si'],['0','No']];
					var homol = new Ext.data.SimpleStore({
					                    fields:['homologoterr','val']
					
					});
					
					homol.loadData(datos);
					
					
					var datos = [['1',perfil.etiquetas.lbsi],['0',perfil.etiquetas.lbno]];
					var esmarina = new Ext.data.SimpleStore({
					                    fields:['esmarina','val']
					
					});
					
					esmarina.loadData(datos);
					
				var anterior= new Ext.data.Store({
			    							autoLoad: true,
			    							url: 'mostrargradomilitar',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idgradomilit"    
			    										},[{
			        										name:'idgradomilit'
			        									},{
			        						 				name: 'dengradomilit'			        						 			
			        						 			},{
			        										name: 'anterior'
			        									},{
			        										name: 'sucesor'
			    										}] 
											)
							})	
				/*var sucesor= new Ext.data.Store({
			    							autoLoad: true,
			    							url: 'mostrargradomilitar',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idgradomilit"    
			    										},[{
			        										name: 'sucesor'
			    										},{
			        										name:'idgradomilit'	
			        									},{
			        						 				name: 'dengradomilit'
			        						 			}] 
											)
							})	*/			
			
					
					
		var col2_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'combo',
						items:[{
							fieldLabel: perfil.etiquetas.lbEsmarina,
							id:"marina",
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
							store:esmarina,
							displayField:'val',
							valueField:'esmarina',
							hiddenName:'esmarina',
							listeners:{
										select:function(c,r,i){
												if(r.data.esmarina==0){
														Ext.getCmp('homologo').disable();
												}
												else
														Ext.getCmp('homologo').enable();
										}
                                                        }
						},{
							fieldLabel:perfil.etiquetas.lbAnterior,
							id:"idanterior",
							editable :false,
							allowBlank:true,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:anterior,
							displayField:'dengradomilit',
							valueField:'idgradomilit',
							hiddenName:'valanterior',
							listeners:{select:function(){
								var anterior = Ext.getCmp('idanterior').getValue();
								var sucesor = Ext.getCmp('idsucesor').getValue();
								
								if(anterior==sucesor)
								mostrarMensaje(3,"Los campos anterior y sucesor no pueden ser iguales.");
								}
								
							}
						},{
							fieldLabel: perfil.etiquetas.lbSucesor,
							id:"idsucesor",
							editable :false,
							allowBlank:true,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:anterior,
							displayField:'dengradomilit',
							valueField:'idgradomilit',
							hiddenName:'valsucesor',
							listeners:{select:function(){
								var anterior = Ext.getCmp('idanterior').getValue();
								var sucesor = Ext.getCmp('idsucesor').getValue();
								
								if(anterior==sucesor)
								mostrarMensaje(3,"Los campos anterior y sucesor no pueden ser iguales.");
								}
								
							}
						}]
		};
		/**
		 * SOLO MODIFICABLES LOS ELEMENTOS DENTRO DE LA COLUMNA
		 */
		var col3_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'datefield',
						items: [{
							xtype:'combo',
							fieldLabel: perfil.etiquetas.lbHomologoentierra,
							id:"homologo",
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
							store:homol,
							disabled:true,
							displayField:'val',
							valueField:'homologoterr',
							hiddenName:'homologoterr'
						},{
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
		//NO MODIFICAR (ES PARA METER LAS TRES COLUMNAS DENTRO DEL FORMULARIO)
		
 		item_GradoM = [col1_FormNomenclador,col2_FormNomenclador,col3_FormNomenclador];
 		 this.cargarStore=function()
			{
			  subcat.load();		      
              //anterior.load();
			}
			this.cargarStoreAnterior=function()
			{
				anterior.load();
			}
 		this.cm = cm_GradoM;
 		this.item = item_GradoM;
 		this.rc = rc_GradoM;
 		/**@type{String} */
 		this.referencia='gradomtar';
	
};
