   
function NomencladorIcono(){
	 Ext.QuickTips.init();
	

    // turn on validation errors beside the field globally
   
	var cm_icon = [
					{ dataIndex: 'icono', sortable: true,header: perfil.etiquetas.lbIcono,id:'expandir',width:160},
					{ dataIndex: 'descripcion', sortable: true,header: perfil.etiquetas.lbDescripcion},
					{ dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura},
					{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
					{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
					];
					
	var rc_icon = [
					{name: 'icono', mapping:'denominacion'},						
					{name: 'descripcion', mapping :'idcalificador'},	
                    {name: 'abrev', mapping :'abreviatura'},					
					{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'}		
								
					];	
	
	var col1_FormNomenclador = {
									columnWidth:.5,
									layout: 'form',									
									//defaultType:'textfield',									
									items: [{
                                            xtype: 'fileuploadfield',
								            id: 'form-file',
								            emptyText: perfil.etiquetas.lbMsgSeleccioneelicono,
								            fieldLabel: perfil.etiquetas.lbIcono,
								            name: 'photo-path'
								           /* buttonCfg: {
								            	text: '',
								                iconCls: 'upload-icon'
								            }*/
										},{	
											xtype:'textfield',
											fieldLabel: perfil.etiquetas.lbabreviatura, //MODIFICLABLE									
											name: 'abrev', //MODIFICLABLE	
											allowBlank:false,									
											anchor:'93%',										
											regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							                maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
                                            regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
											blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
											invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto
										},{
											xtype:'textfield',
											fieldLabel: perfil.etiquetas.lbDescripcion,		
											name: 'orden',
											allowBlank:false,											
											regexText:perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
											blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
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
										fieldLabel: perfil.etiquetas.lbfechafin,
										readOnly :true,
										id: 'idffin',
										name: 'ffin',
										format :'d/m/Y',
										anchor:'90%'							
							}]								
					};

	var item_icono = [col1_FormNomenclador,col2_FormNomenclador];

	this.cm = cm_icon;
 	this.item = item_icono;
 	this.rc = rc_icon;
 	/**@type{String} */
 	this.referencia='icono';	
	
};