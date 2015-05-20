


      
function NomencladorPrefijo(){
Ext.QuickTips.init();


	
		// Ext.form.Field.prototype.msgTarget = 'side';
	// Ext.form.Field.prototype.msgTarget = 'side';
		var cm_esp = [
            		{header: perfil.etiquetas.lbprefijo, width: 100, sortable: true, dataIndex: 'denom',id:'expandir'},
            		{header: perfil.etiquetas.lblugar, width: 100, sortable: true, dataIndex: 'lugar'},
            		{ dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},
            		{header: perfil.etiquetas.lbfechaini, dataIndex: 'fini', sortable: true,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            {header: perfil.etiquetas.lbfechafin,dataIndex: 'ffin', sortable: true,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
        		];
	var rc_esp =  [
           			{name: 'denom', mapping:'prefijo'},
           			{name: 'idpref', mapping:'idprefijo'},
		           	{name: 'lugar', mapping: 'desclugar'},
		           	{name: 'orden', mapping:'orden'},
           			{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		            {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'}
		          ];
		          
	var col1_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel:  perfil.etiquetas.lbprefijo,
							name: 'prefijo',
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,    
							anchor:'93%',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/  
						},{
							fieldLabel: perfil.etiquetas.lblugar,   
							name: 'lugar',              
							allowBlank:false,
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%'
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
								anchor:'100%'
							},{
								fieldLabel: perfil.etiquetas.lbfechafin,
								readOnly :true,
								id: 'idffin',
								name: 'ffin',
								format :'d/m/Y',
								anchor:'100%'
						}]
					};
	var item_esp = [col1_FormNomenclador,col2_FormNomenclador];
 		
		 /**
		  * @public
		  */
 		this.cm = cm_esp;
 		this.item = item_esp;
 		this.rc = rc_esp;
 		/**@type{String} */
 		this.referencia='prefijo';	
	


	
		
		          
	var col1_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel:  perfil.etiquetas.lbprefijo,
							name: 'prefijo',
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,    
							anchor:'93%',
							regex:/^(\W|\w){1,10}$/,
							maskRe:/^(\W|\w){1,10}$/   
						},{
							fieldLabel: perfil.etiquetas.lblugar,   
							name: 'lugar',              
							allowBlank:false,
							regex:/^(\W|\w){1,30}$/,
							maskRe:/^(\W|\w){1,30}$/,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%'
						},{
							fieldLabel: perfil.etiquetas.lborden,
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
	var item_esp = [col1_FormNomenclador,col2_FormNomenclador];
 		
		 /**
		  * @public
		  */
 		this.cm = cm_esp;
 		this.item = item_esp;
 		this.rc = rc_esp;
 		/**@type{String} */
 		this.referencia='prefijo';	
 		this.id = 'idprefijo'; 
	
};