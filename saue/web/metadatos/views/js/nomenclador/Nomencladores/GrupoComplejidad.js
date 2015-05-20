
      
function NomencladorGrupoComplejidad(){
	 Ext.QuickTips.init();   
	var cm_grupcom = [
					{ dataIndex: 'denom', sortable: true,header:perfil.etiquetas.lbdenominacion,id:'expandir',width:98},
					{ dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura,width:98},
					{ dataIndex: 'orden', sortable: true,header:perfil.etiquetas.lborden,width:98},
					{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,width:98,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,width:89,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
				];
					
	var rc_grupcom = [
					{name: 'denom', mapping:'denominacion'},	
					{name: 'idgrupocomplejidad', mapping:'idgrupocomplejidad'},
                    {name: 'abrev', mapping :'abreviatura'},
                    {name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		            {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},
		            {name: 'orden'}
							
								
					];	
					

	var col1_FormNomenclador = {
									columnWidth:.5,
									layout: 'form',									
									defaultType:'textfield',									
									items: [{									
											fieldLabel:perfil.etiquetas.lbdenominacion, //MODIFICLABLE									
											name: 'denom', //MODIFICLABLE	
											allowBlank:false,									
											anchor:'93%',										
											regex:/^\w(\s|\w)\w{1,60}$/,	//,maskRe:/^(\W|\w){1,60}$/,
                                            regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
											//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
											invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto
										},{
											fieldLabel:  perfil.etiquetas.lbabreviatura,		
											name: 'abrev',
											allowBlank:false,											
										    regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
											//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
											invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,										
											anchor:'93%',
											regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							                maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
										},{
											fieldLabel:  perfil.etiquetas.lborden,		
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
								columnWidth:.5,								
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
										fieldLabel:  perfil.etiquetas.lbfechafin,
										readOnly :true,
										id: 'idffin',
										name: 'ffin',
										format :'d/m/Y',
										anchor:'90%'							
							}]								
					};

	var item_grupcom = [col1_FormNomenclador,col2_FormNomenclador];

	this.cm = cm_grupcom;
 	this.item = item_grupcom;
 	this.rc = rc_grupcom;
 	/**@type{String} */
 	this.referencia='grupocomplejidad';	
	
};