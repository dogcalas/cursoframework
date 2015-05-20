

Ext.QuickTips.init();
function NomencladorTecnica(){

		Ext.QuickTips.init();
		// Ext.form.Field.prototype.msgTarget = 'side';
		cm_Tecnica = [
		           { dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:130},
		            { dataIndex: 'codigo', sortable: true,header: perfil.etiquetas.lbcod,width:98},
		            { dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura,width:98},
		            { dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden,width:48},
		            { dataIndex: 'valor', sortable: true,header: perfil.etiquetas.lbvalor,width:57,renderer: changeValor},
		             {dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,width:68,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            { dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,width:70,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		           
		            
		            		            
		        	];
 		rc_Tecnica= [
 		           {name: 'codigo', mapping:'codtecnica'},
		           {name: 'denom', mapping:'dentecnica'},
		           {name: 'abrev', mapping :'abrevtecnica'},
		           {name: 'orden', mapping : 'orden'},
		           {name: 'valor', mapping :'vaplantilla'},
		           {name: 'idvalor', mapping :'vaplantilla'},
		           {name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},	
		           {name: 'idtecnica', mapping :'idtecnica'}
		           ];
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
							fieldLabel:perfil.etiquetas.lbabreviatura, //MODIFICLABLE
							name: 'abrev', 			   //MODIFICLABLE
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
							
							///////////////////////////
							
							
						},{
							xtype:'textfield',
							fieldLabel: perfil.etiquetas.lborden,
							allowBlank:false,
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
		var Datos=[['1', perfil.etiquetas.lbFundamental],['0', perfil.etiquetas.lbNo_Fundamental]];
		
		var st_valor = new Ext.data.SimpleStore({
        						fields: ['idvalor','denvalor'],
        						data : Datos
    						});
    				
		var col2_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'combo',
						items:[{
							fieldLabel: perfil.etiquetas.lbvalor,
							allowBlank:false,
							id:"idvalortecnica",
							editable :false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_valor,
							displayField:'denvalor',
							valueField:'idvalor',
							hiddenName:'idvalor'
						},{ 
							xtype:'textfield',
							fieldLabel: perfil.etiquetas.lbcod,
							name: 'codigo',
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%',
							regex:/^\d{1,20}$/,
                            maskRe:/^\d{1,20}$//////codigo
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
		function changeValor(val){
        if(val == '0'){
              return  perfil.etiquetas.lbNo_Fundamental;  
        }else if(val == '1'){
            
             return  perfil.etiquetas.lbFundamental;  
        }
		}
 		item_Tecnica = [col1_FormNomenclador,col2_FormNomenclador,col3_FormNomenclador]; 		
 		this.cm = cm_Tecnica;
 		this.item = item_Tecnica;
 		this.rc = rc_Tecnica;
 		this.referencia='idtecnica';
	
};