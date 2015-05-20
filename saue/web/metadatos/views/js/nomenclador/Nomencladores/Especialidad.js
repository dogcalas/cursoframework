

    
function NomencladorEspecialidad(){
	Ext.QuickTips.init();
	// Ext.form.Field.prototype.msgTarget = 'side';
	var cm_esp = [
            		{header: perfil.etiquetas.lbdenominacion, width: 100, sortable: true, dataIndex: 'denom',id:'expandir',width:123},
            		{header: perfil.etiquetas.lbabreviatura, width: 100, sortable: true, dataIndex: 'abrev',width:100},
            		{header: perfil.etiquetas.lbCoddelaespecialidad, width: 100, sortable: true, dataIndex: 'code',width:130},
            		{header: perfil.etiquetas.lborden, width: 100, sortable: true, dataIndex: 'orden',width:69},
            		{ dataIndex: 'fini',header: perfil.etiquetas.lbfechaini, width: 100, sortable: true,width:84,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
            		{  dataIndex: 'ffin',header: perfil.etiquetas.lbfechafin, width: 100, sortable: true, width:75,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
        		];
	var rc_esp =  [
           			{name: 'denom', mapping:'denespecialidad'},
		           	{name: 'abrev', mapping :'abrevespecialidad'},
		           	{name: 'code', mapping :'codespecialidad'},		           	
		           	{name: 'idesp', mapping :'idespecialidad'},
           			{name: 'orden'},
           			{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'}
		          ];
		          
	var col1_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel:perfil.etiquetas.lbdenominacion,
							name: 'denom',
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
							
						},{
							fieldLabel: perfil.etiquetas.lbabreviatura,
							name: 'abrev',
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							allowBlank:false,
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
							xtype:'textfield',
							fieldLabel: perfil.etiquetas.lbCoddelaespecialidad,
							name: 'code',
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%',
							regex:/^\d{1,2}$/,
							maskRe:/^\d{1,2}$/
							
							
						},{
							fieldLabel: perfil.etiquetas.lbfechaini,
							readOnly :true,
							name: 'fini',
							id: 'idfini',
							format :'d/m/Y',
							value : new Date(),
							anchor:'90%'
						},{
							fieldLabel:perfil.etiquetas.lbfechafin,
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
 		this.referencia='esp';	
};