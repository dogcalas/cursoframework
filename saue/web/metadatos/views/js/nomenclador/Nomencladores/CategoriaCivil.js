

Ext.QuickTips.init();	    
NomencladorCategoriaCivil = function(){
	Ext.QuickTips.init();	
	var cm_catcivil = [
						{ dataIndex: 'denom', sortable: true,header: perfil.etiquetas.lbdenominacion,id:'expandir',width:188},				
						{ dataIndex: 'abrev', sortable: true,header: perfil.etiquetas.lbabreviatura,width:141},				
						{ dataIndex: 'orden', sortable: true,header:perfil.etiquetas.lborden,width:63},				
						{ dataIndex: 'idsueldo', sortable: true,header:perfil.etiquetas.lbEssueldo ,renderer: change,width:61},
						{ dataIndex: 'fini', sortable: true,header: perfil.etiquetas.lbfechaini,width:69,renderer: Ext.util.Format.dateRenderer('d/m/Y')},				
						{ dataIndex: 'ffin', sortable: true,header: perfil.etiquetas.lbfechafin,width:65,renderer: Ext.util.Format.dateRenderer('d/m/Y')}				
						];
	var rc_catcivil = [
						{name: 'denom', mapping:'dencategcivil'},						
						{name: 'abrev', mapping :'abrevcategcivil'},						
						{name: 'idcatgriacvil', mapping :'idcategcivil'},						
						{name: 'idsueldo', mapping :'essueldo'},
						{name: 'idcatgriacvil', mapping :'idcategcivil'},												
						{name: 'fini',  mapping :'fechaini',type:'date',dateFormat: 'Y-m-d'},
		           {name: 'ffin', mapping :'fechafin',type:'date',dateFormat: 'Y-m-d'},								
						{name: 'orden'}
					];
					
	var col1_FormNomenclador = {
						columnWidth:.5,
						layout: 'form',
						defaultType:'textfield',
						items: [{
							fieldLabel: perfil.etiquetas.lbdenominacion, //MODIFICLABLE
							name: 'denom', 				//MODIFICLABLE
							allowBlank:false,
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							anchor:'93%'//,							regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/  
						},{
							fieldLabel: perfil.etiquetas.lbabreviatura, //MODIFICLABLE
							name: 'abrev', 
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrecto,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrecto,   //MODIFICLable 									                            //regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,   
							regex:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							maskRe:/^([a-zA-Z0-9αινσϊρόΡ,;. ]+ ?[a-zA-Z0-9αινσϊρόΡ,.; ]*)+$/,
							allowBlank:false,
							anchor:'93%'
						},{
							fieldLabel: perfil.etiquetas.lborden,
							name: 'orden',  
							regexText:perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,
							//blankText:perfil.etiquetas.lbMsgEstecampoesobligatorio,
							invalidText : perfil.etiquetas.lbMsgEstevaloresincorrectoOrden,    //regex:/^([a-zA-Z]+?[a-zA-Z]*)+$/,  
							regex:/^\d{1,8}$/,
							maskRe:/^\d{1,8}$/,
							allowBlank:false,
							anchor:'93%'
						}]
					};
		var Datos=[['1', perfil.etiquetas.lbsi],['0', perfil.etiquetas.lbno],['2', perfil.etiquetas.lbninguno]];
		
		var st_sueldo = new Ext.data.SimpleStore({
        						fields: ['idessueldo','denessueldo'],
        						data : Datos
    						});

	var col2_FormNomenclador = {
						columnWidth:.5,						
						layout: 'form',												
						items:[{
								xtype:'datefield',
								fieldLabel: perfil.etiquetas.lbfechaini,
								readOnly :true,
								name: 'fini',
								id: 'idfini',
								format :'d/m/Y',
								value : new Date(),
								anchor:'90%'
							},{
								xtype:'datefield',
								fieldLabel: perfil.etiquetas.lbfechafin,
								readOnly :true,
								id: 'idffin',
								name: 'ffin',
								format :'d/m/Y',
								anchor:'90%'
							},{
								xtype:'combo',
								fieldLabel: perfil.etiquetas.lbEssueldo,
								id:"idsueldo",
								allowBlank:false,
								editable :false,
								triggerAction:'all',
								forceSelection:true,
								emptyText:perfil.etiquetas.lbMsgSeleccioneeltipo,			
								hideLabel:false,
								autoCreate: true,
								mode: 'local',
								forceSelection: true,
								anchor:'93%',
								store:st_sueldo,
								displayField:'denessueldo',
								valueField:'idessueldo',
								hiddenName:'idessueldo'
						}]
					};
					 	function change(val){
        if(val == "0"){
            return  perfil.etiquetas.lbno;     
        }else if(val == "1"){
             return  perfil.etiquetas.lbsi;
        }
         else if(val == "2"){
         return perfil.etiquetas.lbninguno;
        }
        return val;
    };

	var item_catcivil = [col1_FormNomenclador,col2_FormNomenclador];
	
	this.cm = cm_catcivil;
 	this.item = item_catcivil;
 	this.rc = rc_catcivil;
 	/**@type{String} */
 	this.referencia='catgriacvil';

	
};