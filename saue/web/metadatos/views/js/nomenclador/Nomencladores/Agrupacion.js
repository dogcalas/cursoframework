

function NomencladorAgrupacion(){
	Ext.QuickTips.init();	
	var cm_Agrupacion = [
		            { dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir'},
		            { dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura},
		            { dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},
		            { dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            { dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		        	];
 	var rc_Agrupacion = [
		           {name: 'denom', mapping:'denagrupacion'},
		           {name: 'idagrupacion', mapping:'idagrupacion'},
		           {name: 'abrev', mapping :'abrevagrupacion'},
		           {name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'orden', mapping : 'orden'}
		          ];
		          
	var col1_FormNomenclador = {
						columnWidth:.5,
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

 	item_Agrupacion = [col1_FormNomenclador,col2_FormNomenclador];
 	
 	this.cm = cm_Agrupacion;
 	this.item = item_Agrupacion;
 	this.rc = rc_Agrupacion;
 		/**@type{String} */
 	this.referencia='nagrupacion';


 	
};
