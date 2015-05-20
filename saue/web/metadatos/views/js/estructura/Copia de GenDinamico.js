var vCompGen; var vGridGen;
 //Ext.BLANK_IMAGE_URL = '../ext-2.2/resources/images/default/s.gif';
//jsons temporales (para grids)
/**aJSonGrid1  =[{columns:[{id:'expandir',width:100, header: 'Sirvio', dataIndex: 'nombre'},{header: 'Fecha de inicio', width:100, dataIndex: 'finicio'},{header: 'Fecha de fin', width:100, dataIndex: 'ffin'}]},
							{store:{url:'cargargrid.php', rdRoot:'datos', rdId:'nombre', rdTotRec:'totalRec',rdCampos:[{name: 'nombre'},{name: 'finicio'},{name: 'ffin'}]}},
							{title:'titulo'}
						 ];
aJSonGrid2  =[{columns:[{id:'expandir',width:100, header: 'Te dije que sirvio', dataIndex: 'nombre'},{header: 'Fecha de inicio', width:100, dataIndex: 'finicio'},{header: 'Fecha de fin', width:100, dataIndex: 'ffin'}]},
							{store:{url:'cargargrid.php', rdRoot:'datos', rdId:'nombre', rdTotRec:'totalRec',rdCampos:[{name: 'nombre'},{name: 'finicio'},{name: 'ffin'}]}},
							{title:'titulo'}
						 ];
aJSonGrid3  =[{columns:[{id:'expandir',width:100, header: 'que si sirvio', dataIndex: 'nombre'},{header: 'Fecha de inicio', width:100, dataIndex: 'finicio'},{header: 'Fecha de fin', width:100, dataIndex: 'ffin'}]},
							{store:{url:'cargargrid.php', rdRoot:'datos', rdId:'nombre', rdTotRec:'totalRec',rdCampos:[{name: 'nombre'},{name: 'finicio'},{name: 'ffin'}]}},
							{title:'titulo'}
						 ];
aJSonGrid4  =[{columns:[{id:'expandir',width:100, header: 'Claro que sirvio', dataIndex: 'nombre'},{header: 'Fecha de inicio', width:100, dataIndex: 'finicio'},{header: 'Fecha de fin', width:100, dataIndex: 'ffin'}]},
							{store:{url:'cargargrid.php', rdRoot:'datos', rdId:'nombre', rdTotRec:'totalRec',rdCampos:[{name: 'nombre'},{name: 'finicio'},{name: 'ffin'}]}},
							{title:'titulo'}
						 ];
						 
//jsons temporales (para formularios)
aJSonItems = [{cantidad:9},{url :}
							{xtype:'textfield',fieldLabel:'label',id:'id',maxLength:20,name:'nombre',regex:'ss'},
							{xtype:'textfield',fieldLabel:'label',id:'id',maxLength:20,name:'nombre',regex:'ss'},
							{xtype:'textfield',fieldLabel:'label',id:'id',maxLength:20,name:'nombre',regex:'ss'},
							{xtype:'datefield',fieldLabel:'label',id:'id',maxLength:20,name:'nombre',regex:'ss'},
							{xtype:'textfield',fieldLabel:'label',id:'id',maxLength:20,name:'nombre',regex:'ss'},
							{xtype:'datefield',fieldLabel:'label',id:'id',maxLength:20,name:'nombre',regex:'ss'},
							{xtype:'combo',		 fieldLabel:'label Combo',id:'lcombo',maxLength:80,name:'lcidns',regex:null,anchor:'95%',data:[['sdsd','sdfsdf'],['hjgk','fsdfsd'],['ghjkj','fsdf'],['gjkhgjk','fsdf']]},
							{xtype:'textarea', fieldLabel:'label',id:'id',maxLength:20,name:'nombre',regex:'ss'},
							{xtype:'textarea', fieldLabel:'label',id:'id',maxLength:20,name:'nombre',regex:'ss'}];
//funciones para generacion dinamica (formularios)
//Para crear el arreglo de items
**/							

function creaArrayItems(aJSonItems){
	var arrayItems = new Array();
	for (var i=1;i<=aJSonItems[0].cantidad;i++)
		arrayItems.push(dameArrayColumn(aJSonItems,aJSonItems[0].cantidad,i))
	return arrayItems;
}
//Para crear el arreglo de columnas
function dameArrayColumn(aJSonItems,noCol,pos){
		if (noCol == 1) return {columnWidth:1,  layout: 'form',items:dameItem(aJSonItems[pos].xtype,pos,aJSonItems)};
		if (noCol == 2) return {columnWidth:.5, layout: 'form',items:dameItem(aJSonItems[pos].xtype,pos,aJSonItems)};
		if (noCol >= 3) return {columnWidth:.33,layout: 'form',items:dameItem(aJSonItems[pos].xtype,pos,aJSonItems)};
}
//Para devolver un item especifico (El chek y radio por ahora no funciona, hay que acomodarlos.)
function dameItem(xtype,pos,aJSonItems){
	if (xtype == 'textfield')  return {xtype:'textfield',	 fieldLabel: aJSonItems[pos].fieldLabel,id: aJSonItems[pos].id,maxLength:aJSonItems[pos].maxLength,name:aJSonItems[pos].name,regex:aJSonItems[pos].regex,anchor:'95%'};
	if (xtype == 'timefield ') return {xtype:'timefield ', fieldLabel: aJSonItems[pos].fieldLabel,id: aJSonItems[pos].id,maxLength:aJSonItems[pos].maxLength,name:aJSonItems[pos].name,regex:aJSonItems[pos].regex,anchor:'95%'};
	if (xtype == 'textarea')   return {xtype:'textarea',	 fieldLabel: aJSonItems[pos].fieldLabel,id: aJSonItems[pos].id,maxLength:aJSonItems[pos].maxLength,name:aJSonItems[pos].name,regex:aJSonItems[pos].regex,anchor:'95%'};
	if (xtype == 'numberfield')return {xtype:'numberfield',fieldLabel: aJSonItems[pos].fieldLabel,id: aJSonItems[pos].id,maxLength:aJSonItems[pos].maxLength,name:aJSonItems[pos].name,regex:aJSonItems[pos].regex,anchor:'95%'};
	if (xtype == 'datefield')  return {xtype:'datefield',	 fieldLabel: aJSonItems[pos].fieldLabel,id: aJSonItems[pos].id,maxLength:aJSonItems[pos].maxLength,name:aJSonItems[pos].name,regex:aJSonItems[pos].regex,anchor:'95%'};
	if (xtype == 'combo') 		 return {xtype:'combo',			 fieldLabel: aJSonItems[pos].fieldLabel,id: aJSonItems[pos].id,maxLength:aJSonItems[pos].maxLength,name:aJSonItems[pos].name,regex:aJSonItems[pos].regex,anchor:'95%',mode:'local',data:aJSonItems[pos].data,displayField:'displayField',valueField:'valueField',mode:'local',store:new Ext.data.SimpleStore({fields:['displayField','valueField'],data:aJSonItems[pos].data})};
	if (xtype == 'radio') 	 	 return {xtype:'radio',	 		 fieldLabel: aJSonItems[pos].fieldLabel,id: aJSonItems[pos].id,checked:aJSonItems[pos].checked,name:aJSonItems[pos].name,anchor:'95%'};
	if (xtype == 'checkbox') 	 return {xtype:'checkbox',	 fieldLabel: aJSonItems[pos].fieldLabel,id: aJSonItems[pos].id,checked:aJSonItems[pos].checked,name:aJSonItems[pos].name,anchor:'95%'};
}
//funciones para generacion dinamica (Grid)
//Funcion para devolver el Column Model
function dameGridColumn(aJSonGrid1){
	return new Ext.grid.ColumnModel(aJSonGrid1[0].columns);
}
//Funcion para devolver el Store del grid
function dameStoreGrid(aJSonGrid1){
	//lert(aJSonGrid1[1].store.rdCampos[0].name);
	return new Ext.data.Store({
					url: aJSonGrid1[1].store.url,
					autoLoad:true,
					reader:new Ext.data.JsonReader({
						root:aJSonGrid1[1].store.rdRoot,
						id: aJSonGrid1[1].store.rdId,
						totalProperty:aJSonGrid1[1].store.rdTotRec },
				   		 aJSonGrid1[1].store.rdCampos				
					)
				});
}
/**Grid Gestion dinamica
 * @return Ext.grid.GridPanel()
 */
function dameGrid(Json){
	var store =dameStoreGrid(Json);
	var colum =dameGridColumn(Json);
	gdGestionDinamica = new Ext.grid.GridPanel({
	frame:true,
	iconCls:'icon-grid',
	height:200,
	autoExpandColumn:'expandir',
	store:store,
	sm:new Ext.grid.RowSelectionModel({singleSelect:true}),
	cm:colum,
	bbar: new Ext.PagingToolbar({
		pageSize: 5,
		store:store,
		displayInfo: true,
		displayMsg: 'Resultados de {0} - {1} de {2}',
		emptyMsg: "No hay resultados para mostrar."
	})
});
	//gdGestionDinamica.getStore().load();
return gdGestionDinamica;

}

/**Funcion para cargar el Json
*@param {url}
*@return {Ext.Util.Json}.
*/
CargarJson=function(dirurl,params){	
      var json;  
   				Ext.Ajax.request({
   						url: dirurl,
   						success : function(resp,obj)
   									{
      									res=resp.responseText;
      									eval("json="+res);
	  								},
									 params: params
									
    			})
   				return json;
 		};
		
//Ventanas
//Ventana con el FormPanel
function mostrarVCompGen(){
	if(!vCompGen){
		vCompGen = new Ext.Window({
			title:'Componentes generados',
			layout:'fit',
			width:500,
			autoHeight:true,
			modal:true,
			height:200,
			closeAction:'hide',
			items:new Ext.FormPanel({
				labelAlign: 'top',
				autoHeight:true,
				frame:true,
				items:{layout:'column',
					items:creaArrayItems(aJSonItems)
				}
			}),
			buttons:[{icon:'../images/icon/cancelar.png',iconCls:'btn',handler:function(){vCompGen.hide();},text:'Cancelar'
				},{	icon:'../images/icon/anterior.png',iconCls:'btn',handler:function(){},text:'Anterior'
				},{	icon:'../images/icon/aceptar.png',iconCls:'btn',handler:function(){},text:'Aceptar'
			}]
		});
	}
	vCompGen.show(this);
}
//Ventana del grid dinamico
function mostrarVGridGen(){
	if(!vGridGen){
		vGridGen = new Ext.Window({
			title:'Componentes generados',
			layout:'fit',
			width:500,
			autoHeight:true,
			modal:true,
			height:200,
			closeAction:'hide',
			items:gdGestionDinamica,
			buttons:[{icon:'../images/icon/cancelar.png',iconCls:'btn',handler:function(){vGridGen.hide();},text:'Cancelar'
				},{	icon:'../images/icon/anterior.png',iconCls:'btn',handler:function(){},text:'Anterior'
				},{	icon:'../images/icon/aceptar.png',iconCls:'btn',handler:function(){},text:'Aceptar'
			}]
		});
	}
	vGridGen.show(this);
}//del load

//Mostrar una ventana u otra
/*mostrarVCompGen();
 * tbar:[{
			icon:'../images/icon/falta.png',
			iconCls:'btn',
			handler:function(){gdGestionDinamica.reconfigure(dameStoreGrid(aJSonGrid1),dameGridColumn(aJSonGrid1))},
			text:'Cambiar CM y Store 01'
	},{	icon:'../images/icon/falta.png',
			iconCls:'btn',
			handler:function(){gdGestionDinamica.reconfigure(dameStoreGrid(aJSonGrid2),dameGridColumn(aJSonGrid2))},
			text:'Cambiar CM y Store 02'
	}],

mostrarVGridGen();*/