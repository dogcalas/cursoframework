


Ext.QuickTips.init();
function NomencladorNivelUtilizacion(){
	 Ext.QuickTips.init();
	

    // turn on validation errors beside the field globally
   
	var cm_nivutil = [
	{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:180},
		            { dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura,width:70},
		            { dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden,width:50},
		            { dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,width:70,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            { dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,width:70,renderer: Ext.util.Format.dateRenderer('d/m/Y')}					
					];
					
	var rc_nivutil = [
					{name: 'denom', mapping:'denominacion'},						
					{name: 'abrev', mapping :'abreviatura'},
					{name: 'idnivelutilizacion', mapping:'idnivelutilizacion'},	
					{name: 'orden', mapping : 'orden'},
                    {name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
           			{name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'}
							
								
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
											 fieldLabel: perfil.etiquetas.lbabreviatura, //MODIFICLABLE
							                name: 'abrev', 			   //MODIFICLABLE
							                allowBlank:false,
							                /*regexText:'Este valor es incorrecto',
							                                          blankText:'Este campo es obligatorio',
							                                          invalidText : 'Este valor es incorrecto',*/
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
							               
						                  }								
										]											
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
										fieldLabel: perfil.etiquetas.lbfechafin,
										readOnly :true,
										id: 'idffin',
										name: 'ffin',
										format :'d/m/Y',
										anchor:'90%'								
							}]								
					};

	var item_catO = [col1_FormNomenclador,col2_FormNomenclador];

	this.cm = cm_nivutil;
 	this.item = item_catO;
 	this.rc = rc_nivutil;
 	/**@type{String} */
 	this.referencia='nivelutilizacion';	
	
};