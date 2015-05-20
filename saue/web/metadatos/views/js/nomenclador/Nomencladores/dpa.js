Ext.QuickTips.init();	    
NomencladorDPA = function(){
Ext.QuickTips.init();
     	
 		var rc_DPA =     [
			           		{name: 'denom', mapping:'denominacion'},
			           		{name: 'abrev', mapping :'abrev'},
			           		{name: 'iddpa', mapping :'idtipodpa'},
			           		{name: 'idtipodpa', mapping :'tipodpa'}
		            		];
		
		
	    var st_dpa = new Ext.data.Store({
		    							autoLoad: true,
		    							url: 'mostrartipodpa',
		    							reader: new Ext.data.JsonReader(
		    										{
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idtipodpa"    
		    										},[
								      					{ name: 'idtipodpa'},
								      					{name: 'denominacion'}
								      					] 
										)
							});
    						
    
		var col1_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel:perfil.etiquetas.lbdenominacion, //MODIFICLABLE
							name: 'denom', 				//MODIFICLABLE
							allowBlank:false,
							anchor:'93%',		
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
						},{
							xtype:'combo',
							fieldLabel: perfil.etiquetas.lbTipoDPA,
							id:"iddpa",
							editable :false,
							allowBlank:false,
							triggerAction:'all',
							forceSelection:true,
							emptyText: perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_dpa,
							displayField:'denominacion',
							valueField:'idtipodpa',
							hiddenName:'idtipodpa'
						}]
					};
			
		var col2_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: perfil.etiquetas.lbabreviatura, //MODIFICLABLE
							name: 'abrev', 				//MODIFICLABLE
							allowBlank:false,
							anchor:'93%',		
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
						}]
		};
		   
		
 		item_DPA = [col1_FormNomenclador,col2_FormNomenclador];
		 
 		/** @public */
 		
 		this.item = item_DPA;
 		this.rc = rc_DPA;
 		/**@type{String} */
 		this.referencia='dpa';
 		this.id = 'iddpa'; 
 		

};