

Ext.QuickTips.init();	    
NomencladorClasificacion = function(){
	 Ext.QuickTips.init();   
	 var cm_cal = [
					{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbTipoCalif,id:'expandir',width:160},
					//{ dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura},					
					{ dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},
					{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
					];
					
	var rc_cal = [
					{name: 'denom', mapping:'denominacion'},						
					{name: 'idclasificacion', mapping :'idclasificacion'},	
                   // {name: 'abrev', mapping :'abreviatura'},                       
					{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},				
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
										//regex:/^\w(\s|\w)\w{1,60}$/,	//,	maskRe:/^(\W|\w){1,60}$/,
	                                    regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
										//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
										invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto
									},/*{									
										fieldLabel: perfil.etiquetas.lbabreviatura, //MODIFICLABLE									
										name: 'abrev', //MODIFICLABLE	
										allowBlank:false,									
										anchor:'93%',										
										regex:/^(\W|\w){1,60}$/,
										maskRe:/^(\W|\w){1,60}$/,
	                                    regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
										blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
										invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto
									},*/{
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


	var col3_FormNomenclador = {								
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

	var item_tipocal = [col1_FormNomenclador,col3_FormNomenclador];

	this.cm = cm_cal;
 	this.item = item_tipocal;
 	this.rc = rc_cal;
 	/**@type{String} */
 	this.referencia='tipocalificador';	
	
};