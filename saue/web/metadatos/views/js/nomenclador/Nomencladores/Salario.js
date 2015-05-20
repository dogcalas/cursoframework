

function NomencladorSalario(){
	 Ext.QuickTips.init();
	

    // turn on validation errors beside the field globally
   
	var cm_sal = [
					{ dataIndex: 'denom', sortable: true,header:perfil.etiquetas.lbdenominacion,id:'expandir',width:172},
					{ dataIndex: 'grupo', sortable: true,header: perfil.etiquetas.lbgrupocomple,width:130},
					{ dataIndex: 'salario', sortable: true,header: perfil.etiquetas.lbsalario,width:78},
					{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,width:98,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,width:98,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{dataIndex: 'orden', sortable: true,header:'Orden',width:98},
					{dataIndex: 'tarifa', sortable: true,header:'Tarifa',width:98}
					 
					];
					
	var rc_sal = [
					{name: 'salario', mapping :'salario'},	
					{name: 'idsalario', mapping :'idsalario'},	
					{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		            {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},		
					{name: 'denom', mapping:'NomEscalasalarial.denominacion'},	
					{name: 'idescalasalarial', mapping:'idescalasalarial'},	
					{name: 'idgrupocomplejidad', mapping :'idgrupocomplejidad'},	
					{name: 'grupo', mapping :'NomGrupocomple.denominacion'},
					{name: 'orden', mapping:'orden'},
					{name: 'tarifa', mapping:'tarifa'}	
								
								
					];	
	var st_escala = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrarescalasalarial',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idescalasalarial"    
			    										},[{
			        										name: 'idescalasalarial'
			        						 			},{
			        						 				name: 'denominacion'
			        						 			}] 
											)
							});
							
	var st_Grupo = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrargrupocomplejidad',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idgrupocomplejidad"    
			    										},[{
			        										name: 'idgrupocomplejidad'
			        						 			},{
			        						 				name: 'denominacion'
			        						 			}] 
											)
							});
	
	var col1_FormNomenclador = {
									columnWidth:.5,
									layout: 'form',									
																		
									items: [{		
										    xtype:'combo',
											fieldLabel: perfil.etiquetas.lbescalasalarial, //MODIFICLABLE									
											name: 'denominacion', //MODIFICLABLE												
											allowBlank:false,
											id:"idescala",
											editable :false,
											triggerAction:'all',
											forceSelection:true,
											emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
											hideLabel:false,
											autoCreate: true,
											mode: 'local',
											forceSelection: true,
											anchor:'93%',
											store:st_escala,
											displayField:'denominacion',
											valueField:'idescalasalarial',
											hiddenName:'idescalasalarial'
											
															
										},{
										    xtype:'combo',
											fieldLabel:  perfil.etiquetas.lbgrupocomple, //MODIFICLABLE									
											name: 'grupo', //MODIFICLABLE												
											allowBlank:false,
											id:"idgrupo",
											editable :false,
											triggerAction:'all',
											forceSelection:true,
											emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
											hideLabel:false,
											autoCreate: true,
											mode: 'local',
											forceSelection: true,
											anchor:'93%',
											store:st_Grupo,
											displayField:'denominacion',
											valueField:'idgrupocomplejidad',
											hiddenName:'idgrupocomplejidad'
										},{ 
										    xtype:'textfield',
											fieldLabel:  perfil.etiquetas.lbsalario, //MODIFICLABLE
											name: 'salario', 				//MODIFICLABLE
											allowBlank:false,
										    regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
											//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
											invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
											anchor:'93%',
											//regex:/^\d{1,8}$/,
							               // maskRe:/^\d{1,8}$/
											regex:/^((\d+(\.\d*)?)|((\d*\.)?\d+))$/,
							                maskRe: /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/
											
										},{ 
										    xtype:'textfield',
											fieldLabel: 'Orden' , //MODIFICLABLE
											name: 'orden', 				//MODIFICLABLE
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
								columnWidth:.5,								
								layout: 'form',								
								defaultType:'datefield',								
								items: [{								
										fieldLabel:perfil.etiquetas.lbfechaini,
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
											fieldLabel: 'Tarifa', //MODIFICLABLE
											name: 'tarifa', 				//MODIFICLABLE
											allowBlank:false,
										    regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
											//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
											invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
											anchor:'93%',
											regex:	/^((\d+(\.\d*)?)|((\d*\.)?\d+))$/
							                //maskRe: /^((\d+(\.\d*)?)|((\d*\.)?\d+))$/
											
										}]								
					};

	var item_salario = [col1_FormNomenclador,col2_FormNomenclador];
    this.cargarStore=function()
	{
	  st_escala.load();
      st_Grupo.load();
	}
	this.cm = cm_sal;
 	this.item = item_salario;
 	this.rc = rc_sal;
 	/**@type{String} */
 	this.referencia='salario';	
	
};