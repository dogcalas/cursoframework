
Ext.QuickTips.init();	    
NomencladorCalificador = function(){
	 Ext.QuickTips.init();   
	 var cm_cal = [
					{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:160},
					{ dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura},
					{ dataIndex: 'codigo', sortable: true,header: perfil.etiquetas.lbcod},
					{ dataIndex: 'tcalific', sortable: true,header:perfil.etiquetas.lbTipocalificador},
					{ dataIndex: 'categocup', sortable: true,header: perfil.etiquetas.lbCategoriaocupacional},					
					{ dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},
					{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
					];
					
	var rc_cal = [
					{name: 'denom', mapping:'denominacion'},	
					{name: 'idtipocalificador', mapping :'idtipocalificador'},		
					{name: 'idcalificador', mapping :'idcalificador'},	
                    {name: 'abrev', mapping :'abreviatura'},
                    {name: 'codigo', mapping :'codigo'},
                    {name: 'tcalific', mapping :'NomTipoCalificador.denominacion'},
                    {name: 'categocup', mapping :'NomCategocup.dencategocup'},
                    {name: 'idcategocup', mapping :'idcategocup'},                    
					{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		            {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},			
					{name: 'orden'}				
					];	
					
					var st_tipocalif = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrartipocalificador',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idtipocalificador"    
			    										},[{
			        										name: 'idtipocalificador'	},{
			        						 				name: 'denominacion'}] 
											)
							});			
							
			var st_categocup = new Ext.data.Store({
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
										fieldLabel: perfil.etiquetas.lbdenominacion, //MODIFICLABLE									
										name: 'denom', //MODIFICLABLE	
										allowBlank:false,									
										anchor:'93%',										
										regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							            maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
	                                    regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
										//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
										invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto
									},{									
										fieldLabel: perfil.etiquetas.lbabreviatura, //MODIFICLABLE									
										name: 'abrev', //MODIFICLABLE	
										allowBlank:false,									
										anchor:'93%',										
										regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							            maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
	                                    regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
										//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
										invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto
									},{
										fieldLabel:perfil.etiquetas.lborden,		
										name: 'orden',
										allowBlank:false,											
										regexText:perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
										//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
										invalidText : perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,											
										anchor:'93%',
										regex:/^\d{1,8}$/,
	                                    maskRe:/^\d{1,8}$/
									}]											
					};
						
	var col2_FormNomenclador = {
								columnWidth:.33,
								layout: 'form',
								defaultType:'combo',
								items:[{
									fieldLabel:perfil.etiquetas.lbTipocalificador,
									id:"idtipo",
									editable :false,
									allowBlank:false,
									triggerAction:'all',
									forceSelection:true,
									emptyText:perfil.etiquetas.lbTitMsgSeleccioneeltipo,					
									hideLabel:false,
									autoCreate: true,
									mode: 'local',
									anchor:'93%',
									store:st_tipocalif,
									displayField:'denominacion',
									valueField:'idtipocalificador',
									hiddenName:'idtipocalificador'
									
								
		                      },{
								    xtype:'textfield',
									fieldLabel: perfil.etiquetas.lbcod, //MODIFICLABLE									
									name: 'codigo',  //MODIFICLABLE	
									allowBlank:false,									
									anchor:'93%',										
									regex:/^\S{1,8}$/,
									maskRe:/^\S{1,8}$/,
                                    regexText:perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
									//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
									invalidText : perfil.etiquetas.lbMsgEstevaloresincorrectoOrden
									},{
										
									fieldLabel:perfil.etiquetas.lbCategoriaocupacional,
									id:"idcateg",
									editable :false,
									allowBlank:false,
									triggerAction:'all',
									forceSelection:true,
									emptyText:perfil.etiquetas.lbTitMsgSeleccioneeltipo,					
									hideLabel:false,
									autoCreate: true,
									mode: 'local',
									anchor:'93%',
									store:st_categocup,
									displayField:'dencategocup',
									valueField:'idcategocup',
									hiddenName:'idcategocup'
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

	var item_catO = [col1_FormNomenclador,col2_FormNomenclador,col3_FormNomenclador];
    this.cargarStore=function()
	{
	  st_tipocalif.load();
      st_categocup.load();
	}
	this.cm = cm_cal;
 	this.item = item_catO;
 	this.rc = rc_cal;
 	/**@type{String} */
 	this.referencia='calificador';	
	
};