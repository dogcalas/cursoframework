NomencladorOrgano = function(){
 		var cm_Organo = [
		            	{ dataIndex: 'denom', sortable: true,header: "Denominacion",id:'expandir',width:160},
		            	{ dataIndex: 'abrev', sortable: true,header: "Abreviatura"},
		            	{ dataIndex: 'orden', sortable: true,header: "Orden"},
		            	{ dataIndex: 'nivel', sortable: true,header: "Nivel"},
		            	{ dataIndex: 'fini', sortable: true,header: "Fecha Inicio"},
		            	{ dataIndex: 'ffin', sortable: true,header: "Fecha Fin"}
		        		];
		        	
		var rc_Organo = [
		           		{name: 'denom', mapping:'denorgano'},
		           		{name: 'abrev', mapping :'abrevorgano'},
		           		{name: 'idorgano', mapping :'idorgano'},
		           		{name: 'espec', mapping :'NomEspecialidad.denespecialidad'},
		           		{name: 'idespecialidad', mapping :'NomEspecialidad.idespecialidad'},
		           		{name: 'nivel',  mapping :'NomNivelestr.dennivelestr'},
		           		{name: 'idnivelestr',  mapping :'NomNivelestr.idnivelestr'},
		           		{name: 'fini',  mapping :'fechaini'},
		           		{name: 'ffin', mapping :'fechafin'},
		           		{name: 'orden'},
		           		{name: 'orden'},
		           		{name: 'orden'}
		          		];
  		var st_DenomEspecialidad = new Ext.data.Store({
			    							autoLoad: true,
			    							url: 'mostrarespecialidades',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idespecialidad"    
			    										},[{
			        										name: 'idespecialidad'
			        						 			},{
			        						 				name: 'denespecialidad'
			        						 			}] 
											)
							});
		var st_DenomNiveles = new Ext.data.Store({
			    							autoLoad: true,
			    							url: 'mostrarniveles',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idnivel"    
			    										},[{
			        										name: 'idnivelestr'
			        						 			},{
			        						 				name: 'dennivelestr'
			        						 			}] 
											)
							});
		var col1_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: 'Denominacion',
							name: 'denom',
							allowBlank:false,
							blankText:'Este campo es requerido',
							anchor:'93%',
							regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,
							regexText:'Este valor es incorrecto',
							blankText:'Este campo es obligatorio',
							invalidText : 'Este valor es incorrecto'//   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
						},{
							fieldLabel: 'Abreviatura',
							name: 'abrev',
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
							allowBlank:false,
							blankText:'Este campo es requerido',
							anchor:'93%'
						},{
							fieldLabel: 'Orden',
							name: 'orden',
							//regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,   //   /^([a-zA-Z��������\d]+ ?[a-zA-Z��������\d]*)+$/,
							allowBlank:false,
							blankText:'Este campo es requerido',
							anchor:'93%'
						}]
					};
		
		var col2_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'combo',
						items:[{
							fieldLabel: 'Nivel',
							id:"idcbnivel",
							editable :false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:'Seleccione el tipo..',					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_DenomNiveles,
							displayField:'dennivelestr',
							valueField:'idnivelestr',
							hiddenName:'idnivelestr'
						},{
							fieldLabel: 'Especialidad',
							id:"idcbesp",
							editable :false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:'Seleccione el tipo..',					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_DenomEspecialidad,
							displayField:'denespecialidad',
							valueField:'idespecialidad',
							hiddenName:'idespecialidad'
						}]
		};
		
		var col3_FormNomenclador = {
						columnWidth:.33,
						layout: 'form',
						defaultType:'datefield',
						items: [{
							fieldLabel: 'Fecha inicio',
							readOnly :true,
							name: 'fechaini',
							format :'d/m/Y',
							value : new Date(),
							anchor:'90%'
						},{
							fieldLabel: 'Fecha Fin',
							readOnly :true,
							name: 'fechafin',
							format :'d/m/Y',
							anchor:'90%'
						}]
					};
		/*var col3_FormNomenclador = {
						columnWidth: 33,
						layout: 'form',
						defaultType:'datefield',
						items: [{
							fieldLabel: 'Fecha inicio',
							readOnly :true,
							name: 'fechaini',
							format :'d/m/Y',
							value : new Date(),
							anchor:'50%'
						},{
							fieldLabel: 'Fecha Fin',
							readOnly :true,
							name: 'fechafin',
							format :'d/m/Y',
							anchor:'50%'
						}]
		};*/

 		var item_Organo = [col1_FormNomenclador,col2_FormNomenclador,col3_FormNomenclador];
 		
		 /**
		  * @public
		  */
 		this.cm = cm_Organo;
 		this.item = item_Organo;
 		this.rc = rc_Organo;
 		/**@type{String} */
 		this.referencia='organo';
};