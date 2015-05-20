/**
 * @author llrojas
 */
NomencladorTipoCifra = function(){
	/** MODIFICABLE
	 * AQUI VAN LAS COLUMNAS DEL GRID
	 */
 		cm_Organo = [
		            { dataIndex: 'denom', sortable: true,header: "Denominacion",id:'expandir',width:160},
		            { dataIndex: 'escifra', sortable: true,header: "Es Cifra Cargo"},
		            { dataIndex: 'esdes', sortable: true,header: "Es Descargable"},
		            { dataIndex: 'fini', sortable: true,header: "Fecha Inicio"},
		            { dataIndex: 'ffin', sortable: true,header: "Fecha Fin"}
		        	];
		       
/** ---------------------- eSTE ES EL RECOD DEL GRID-  ------------------
 		 */
 		/**
		 * SOLO MODIFICABLES NAME Y LOS MAPPING
		 */
 		rc_Organo = [
		           {name: 'denom', mapping:'dencifra'},
		           {name: 'escifra', mapping :'escifra'},
		           {name: 'esdes', mapping: 'esdescifra'},	         
		           {name: 'fini',  mapping :'fechaini'},
		           {name: 'ffin', mapping :'fechafin'}
		            ]
		 /**
		  * @public
		  */
		this.id = 'idorgano'; 
		this.idvalor;
 		this.cm_Organo = cm_Organo;
 		this.item_Organo = item_Organo;
 		this.rc_Organo = rc_Organo;
 		/**@type{String} */
 		this.url='organo';
 		this.Params= function(id){
 			return {idorgano:id}
 		 };
 		

};