
function NomencladorModulo(){
		Ext.QuickTips.init();
		//Ext.form.Field.prototype.msgTarget = 'side';
		/**
		 * ESTA ES LA PARTE PARA QUE SE MUESTRE EL GRID
		 */
		var cm_Modulo = [
		            { dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir'},		           
		            { dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},
		            { dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            { dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		        		   ];
		        	
		        	
			
		var rc_Modulo = [
		           			{name: 'denom', mapping:'denmodulo'},
		           			{name: 'idmodulo', mapping:'idmodulo'},
		           			{name: 'orden', mapping:'orden'},
		           			{name: 'fini',  mapping :'fechaini'},
		           			{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'}
		               		];
		          
		 /**
		  * ESTOS SON LOS ELEMENTO QUE VAN EN EL FORMULARIO
		  */         
		 
		var col1_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: perfil.etiquetas.lbdenominacion, //MODIFICLABLE
							name: 'denom', 				//MODIFICLABLE
							allowBlank:false,
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%'
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/  
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
		//NO MODIFICAR (ES PARA METER LAS TRES COLUMNAS DENTRO DEL FORMULARIO)
 		item_Modulo = [col1_FormNomenclador,col2_FormNomenclador];
 		
		 /**
		  * @public
		  */
 		this.cm = cm_Modulo;
 		/**
		  * @public
		  */
 		this.rc = rc_Modulo;
 		/**
		  * 
		  */
 		this.item = item_Modulo;
 		this.size = 
 		/**
		  * @public
 		  *@type{String} */
 		this.referencia='modulo';
	
}
