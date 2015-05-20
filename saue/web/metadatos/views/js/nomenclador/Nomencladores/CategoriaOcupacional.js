

Ext.QuickTips.init();	    
NomencladorCategoriaOpcnal = function(){
	 Ext.QuickTips.init();
	

    // turn on validation errors beside the field globally
   
	var cm_catO = [
					{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:160},
					{ dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura},
					{ dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},
					{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
					];
					
	var rc_catO = [
					{name: 'denom', mapping:'dencategocup'},						
					{name: 'idcatgriaocpnal', mapping :'idcategocup'},				
					{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},
                    {name: 'abrev', mapping :'abreviatura'},					
					{name: 'orden'}				
					];	
	
	var col1_FormNomenclador = {
									columnWidth:.5,
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
											fieldLabel:perfil.etiquetas.lbabreviatura, //MODIFICLABLE									
											name: 'abrev', //MODIFICLABLE	
											allowBlank:false,									
											anchor:'93%',										
											regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							                maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
                                            regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
											//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
											invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto
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
							}]								
					};

	var item_catO = [col1_FormNomenclador,col2_FormNomenclador];

	this.cm = cm_catO;
 	this.item = item_catO;
 	this.rc = rc_catO;
 	/**@type{String} */
 	this.referencia='catgriaocpnal';	
	
};