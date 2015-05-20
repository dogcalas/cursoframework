NomencladorSubCategoria = function(){
		
		/**
		 * ESTA ES LA PARTE PARA QUE SE MUESTRE EL GRID
		 */
		var cm_Categoria = [
		            		{ dataIndex: 'denom', sortable: true,header: "Denominacion",id:'expandir',width:160},
		            		{ dataIndex: 'orden', sortable: true,header: "Orden"},
		            		{ dataIndex: 'nivel', sortable: true,header: "Nivel"},
		            		{ dataIndex: 'fini', sortable: true,header: "Fecha Inicio"},
		            		{ dataIndex: 'ffin', sortable: true,header: "Fecha Fin"}
		        		   ];
		        	
		        	
			
		var rc_Categoria = [
		           			{name: 'denom', mapping:'dencategorias'},
		           			{name: 'abrev', mapping :'abrevcategorias'},
		           			{name: 'fini',  mapping :'fechaini'},
		           			{name: 'ffin', mapping :'fechafin'},
		           			{name: 'nivel', mapping :'NomNivelestr.dennivelestr'},
		           			{name: 'orden', mapping : 'ordencategoria'}
		               		];
		          
		 /**
		  * ESTOS SON LOS ELEMENTO QUE VAN EN EL FORMULARIO
		  */         
		 
		var col1_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: 'Denominacion', //MODIFICLABLE
							name: 'denom', 				//MODIFICLABLE
							allowBlank:false,
							blankText:'Este campo es requerido',
							anchor:'93%',
							regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/  
						},{
							fieldLabel: 'Orden',
							name: 'orden',
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,  
							allowBlank:false,
							blankText:'Este campo es requerido',
							anchor:'93%'
						}]
					};

		var col2_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'datefield',
						items: [{
							fieldLabel: 'Fecha inicio',
							readOnly :true,
							name: 'fechaini',
							format :'d/m/Y',
							value : new Date(),
							anchor:'95%'
						},{
							fieldLabel: 'Fecha Fin',
							readOnly :true,
							name: 'fechafin',
							format :'d/m/Y',
							value : new Date(),
							anchor:'95%'
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
 		/**
		  * @public
 		  *@type{String} */
 		this.referencia='categoria';
	
}
