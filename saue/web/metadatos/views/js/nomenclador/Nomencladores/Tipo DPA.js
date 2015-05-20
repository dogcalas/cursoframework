
    
function NomencladorTipoDPA(){	 
Ext.QuickTips.init(); 
 		var cm_Tipodpa = [
		            	{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:160},
		            	{ dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden}
		        		];
		        	
 		var rc_Tipodpa = [
			           		{name: 'denom', mapping:'denominacion'},
			           		{name: 'orden', mapping:'orden'},
			           		{name: 'idtipodpa', mapping:'idtipodpa'}
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
						}]
					};
		var col2_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: perfil.etiquetas.lborden, //MODIFICLABLE
							name: 'orden', 				//MODIFICLABLE
							allowBlank:false,
							anchor:'93%',		
							regex:/^\d{1,8}$/,
                            maskRe:/^\d{1,8}$/
						}]
		};
		
 		item_Tipodpa = [col1_FormNomenclador,col2_FormNomenclador];
		 
 		/** @public */
 		this.cm = cm_Tipodpa;
 		this.item = item_Tipodpa;
 		this.rc = rc_Tipodpa;
 		/**@type{String} */
 		this.referencia='dpa';
 		this.id = 'iddpa'; 
 		

};