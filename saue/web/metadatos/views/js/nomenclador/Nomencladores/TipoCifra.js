
function NomencladorTipoCifra(){
Ext.QuickTips.init();
 //Ext.form.Field.prototype.msgTarget = 'side';
 		var cm_TipoCifra = [
		            	{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:160},
		            	{ dataIndex: 'escifra', sortable: true,header: perfil.etiquetas.lbesccargo,renderer: change},
						{ dataIndex: 'orden', sortable: true,header: perfil.etiquetas.lborden},
		            	{ dataIndex: 'esdes', sortable: true,header: perfil.etiquetas.lbesdesc,renderer: changeValor},
		            	{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,renderer: Ext.util.Format.dateRenderer('d/m/Y')},
		            	{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,renderer: Ext.util.Format.dateRenderer('d/m/Y')}
		        		];
		        	
 		var rc_TipoCifra = [
			           		{name: 'denom', mapping:'dentipocifra'},
			           		{name: 'idtipocifra', mapping:'idtipocifra'},
			           		{name: 'escifra', mapping :'escifracargo'},
			           		{name: 'esdes', mapping: 'esdescargable'},	 
							{name: 'orden'},								
			           		{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           			{name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'}	
		            		];
		var Datos=[['1', perfil.etiquetas.lbsi],['0', perfil.etiquetas.lbno]];
		
		var st_cifracargo = new Ext.data.SimpleStore({
        						fields: ['idcifracargo','dencifracargo'],
        						data : Datos
    						});
    						
    	var st_desacargable = new Ext.data.SimpleStore({
        						fields: ['iddesacargable','dendesacargable'],
        						data : Datos
    						});
          
		var col1_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel:  perfil.etiquetas.lbdenominacion, //MODIFICLABLE
							name: 'denom', 				//MODIFICLABLE
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							anchor:'93%',		
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/
						},{
							xtype:'combo',
							fieldLabel: perfil.etiquetas.lbesccargo,
							id:"escifra",
							editable :false,
							allowBlank:false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_cifracargo,
							displayField:'dencifracargo',
							valueField:'idcifracargo',
							hiddenName:'idcifracargo'
						},{
							xtype:'combo',
							fieldLabel: perfil.etiquetas.lbesdesc,
							id:"esdes",
							name:'nesdes',
							editable :false,
							allowBlank:false,
							triggerAction:'all',
							forceSelection:true,
							emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,					
							hideLabel:false,
							autoCreate: true,
							mode: 'local',
							forceSelection: true,
							anchor:'93%',
							store:st_desacargable,
							displayField:'dendesacargable',
							valueField:'iddesacargable',
							hiddenName:'iddesacargable'
						}]
					};
		/**
		 * COL
		 */			
		var col2_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'datefield',
						items:[{
							
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
						},{
						
						xtype:'textfield',
						fieldLabel: perfil.etiquetas.lborden,
						name: 'orden',							
						allowBlank:false,
						regexText:perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
						//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
						invalidText : perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
						anchor:'93%',
						regex:/^\d{1,8}$/,
						maskRe:/^\d{1,8}$/
						
							
						}]
		};
		   	function change(val){
        if(val == "0"){
            return  perfil.etiquetas.lbno;     
        }else if(val == "1"){
             return  perfil.etiquetas.lbsi;
        }
        return val;
    };
    	function changeValor(val){
        if(val == '0'){
            return  perfil.etiquetas.lbno;     
        }else if(val == '1'){
             return  perfil.etiquetas.lbsi;
        }
        return val;
    };
    


 		item_TipoCifra = [col1_FormNomenclador,col2_FormNomenclador];
		 
 		/** @public */
 		this.cm = cm_TipoCifra;
 		this.item = item_TipoCifra;
 		this.rc = rc_TipoCifra;
 		/**@type{String} */
 		this.referencia='tipocifra';
 		this.id = 'idorgano'; 
 		

};