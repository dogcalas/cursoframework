
NomencladorNivelEstructural = function(){
	
	var cm_NivelE = [
		            { dataIndex: 'denom', sortable: true,header: "Denominacion",id:'expandir',width:160},
		            { dataIndex: 'abrev', sortable: true,header: "Abreviatura"},
		            { dataIndex: 'orden', sortable: true,header: "Orden"},
		            { dataIndex: 'fini', sortable: true,header: "Fecha inicio"},
		            { dataIndex: 'ffin', sortable: true,header: "Fecha fin"}
		        	];
 	rc_NivelE = [
		           {name: 'denom', mapping:'dennivel'},
		           {name: 'abrev', mapping :'abrevnivel'},
		           {name: 'fini',  mapping :'fechaini'},
		           {name: 'ffin', mapping :'fechafin'},
		           {name: 'orden', mapping : 'ordennivel'}
		          ];
		          
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
							fieldLabel: 'Abreviatura', //MODIFICLABLE
							name: 'abrev', 			   //MODIFICLABLE
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,   
							allowBlank:false,
							blankText:'Este campo es requerido',
							anchor:'93%'
						},{
							fieldLabel: 'Orden',
							name: 'orden',
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,  
							allowBlank:false,
							blankText:'Este campo es requerido',
							anchor:'93%'
						}]
					};
		/**
		 * SOLO MODIFICABLES LOS ELEMENTOS DENTRO DE LA COLUMNA
		 */			
		
		/**
		 * SOLO MODIFICABLES LOS ELEMENTOS DENTRO DE LA COLUMNA
		 */
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
							fieldLabel: 'Fecha fin',
							readOnly :true,
							name: 'fechafin',
							format :'d/m/Y',
							value : new Date(),
							anchor:'95%'
						}]
		};
		//NO MODIFICAR (ES PARA METER LAS TRES COLUMNAS DENTRO DEL FORMULARIO)
 		item_NivelE = [col1_FormNomenclador,col2_FormNomenclador];
		 /**
		  * @public
		  */
		this.id = 'idnivel'; 
		this.idvalor;
 		this.cm_NivelE = cm_NivelE;
 		this.item_NivelE = item_NivelE;
 		this.rc_NivelE = rc_NivelE;
 		/**@type{String} */
 		this.url='nivel';
 		this.Params= function(id){
 			return {idnivel:id}

}
}