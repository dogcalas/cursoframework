
      
function NomencladorSubCategoria(){	   
	    Ext.QuickTips.init();
	    
	    /**
		 * ESTA ES LA PARTE PARA QUE SE MUESTRE EL GRID
		 */
		var cm_Categoria = [
		            		{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir'},
		            		{ dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},
		            		{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            		{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		        		   ];
		        	
		        	
			
		var rc_Categoria = [
		           			{name: 'denom', mapping:'densubcateg'},
		           			{name: 'idsbcategorias', mapping:'idgsubcateg'},		         
		           			{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},
		           			{name: 'orden', mapping : 'orden'}
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
							name: 'denom', 	
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/, 
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,                //MODIFICLABLE   35 elementos
							allowBlank:false,
							anchor:'93%'
							
						},{
							fieldLabel: perfil.etiquetas.lborden,   //2 elementos
							name: 'orden',
							regex:/^\d{1,8}$/,
							maskRe:/^\d{1,8}$/,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
							allowBlank:false,
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
 		item_Categorias = [col1_FormNomenclador,col2_FormNomenclador];
 		
		 /**
		  * @public
		  */
 		this.cm = cm_Categoria;
 		/**
		  * @public
		  */
 		this.rc = rc_Categoria;
 		/**
		  * 
		  */
 		this.item = item_Categorias;
 		//this.size = 
 		/**
		  * @public
 		  *@type{String} */
 		this.referencia='categoria';
	
}

