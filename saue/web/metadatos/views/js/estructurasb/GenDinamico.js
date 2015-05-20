 var vCompGen; var vGridGen; var btnEliminar; var btnModificar,root,root_comp; var btnAdicionar; var selecModeGrid;var loader_comp; var store;var accion;var urlForm;var filaSelecGrid;var nodo;var nodosel;var idtabla;var loaderEstructura;var win;var btOrganigrama;var mytree;
 var perfil = window.parent.UCID.portal.perfil;
 var idLugar,idesp;
 Ext.MessageBox.buttonText.yes = "Si";
Ext.MessageBox.buttonText.ok = "Aceptar";
			
	

function creaArrayItems(aJSonItems){
	var arrayItems = new Array();
	for (var i=1;i<=aJSonItems[0].cantidad +1;i++)
		arrayItems.push(dameArrayColumn(aJSonItems,aJSonItems[0].cantidad,i))
		//alert(arrayItems[3].fieldLabel);
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
	var exp = null;
	if(aJSonItems[pos].regex!=""){
		eval("exp="+aJSonItems[pos].regex);
		}
		if (xtype == 'textfield')
				return {
					xtype:'textfield',
					allowBlank:false,
					fieldLabel: aJSonItems[pos].fieldLabel,
					id: aJSonItems[pos].id,
					maxLength:aJSonItems[pos].maxLength,
					name:aJSonItems[pos].name,
					regex:exp,
					anchor:'95%',
					listeners :{ beforerender:function(){
											  if(aJSonItems[pos].value != "")this.value= aJSonItems[pos].value;											    
											  if(this.id=='iddpa1'){idLugar =  aJSonItems[pos].valueid;}
											  if(this.id=='idespecialidad1')idesp =  aJSonItems[pos].valueid;											 
											   },
								focus:function(){
								    if(this.id=='iddpa1')
									   {
									   	 
									  	 abrirTree();
									   //this.readOnly=true;
									   }
									if(this.id=='idespecialidad1')   {
										 
										abrirTreeEspecialidad();
									}
								}			   
								}
					};
	if (xtype == 'timefield ')
	return {
			xtype:'timefield ',
			allowBlank:false,
			fieldLabel: aJSonItems[pos].fieldLabel,
			id: aJSonItems[pos].id,
			maxLength:aJSonItems[pos].maxLength,
			name:aJSonItems[pos].name,
			regex:aJSonItems[pos].regex,
			anchor:'95%',
			format:'d/m/Y',
			listeners :{ beforerender:function(){
								  if(aJSonItems[pos].value != "")
								   this.value= aJSonItems[pos].value;
								   }
					}
			};
	if (xtype == 'textarea') 
		return {
				xtype:'textarea',
				allowBlank:false,
				fieldLabel: aJSonItems[pos].fieldLabel,
				id: aJSonItems[pos].id,
				maxLength:aJSonItems[pos].maxLength,
				name:aJSonItems[pos].name,
				regex:aJSonItems[pos].regex,
				anchor:'95%',
				listeners :{ beforerender:function(){
								  if(aJSonItems[pos].value != "")
								   this.value= aJSonItems[pos].value;
								   }
					}
				};
	if (xtype == 'numberfield')
		return {
				xtype:'numberfield',
				allowBlank:false,
				fieldLabel: aJSonItems[pos].fieldLabel,
				id: aJSonItems[pos].id,
				maxLength:aJSonItems[pos].maxLength,
				name:aJSonItems[pos].name,
				regex:aJSonItems[pos].regex,
				anchor:'95%',
				listeners :{ beforerender:function(){
								  if(aJSonItems[pos].value != "")
								   this.value= aJSonItems[pos].value;
								   }
					}
				};
	if (xtype == 'datefield')
	 {
	 
	    var valor=null;
		 if(aJSonItems[pos].id=="fi")
		  {
		  valor=new Date();
		 
		  }
		 else
		 valor=aJSonItems[pos].value
		
	return {
			xtype:'datefield',			
			fieldLabel: aJSonItems[pos].fieldLabel,
			id: aJSonItems[pos].id,
			maxLength:aJSonItems[pos].maxLength,
			name:aJSonItems[pos].name,
			regex:aJSonItems[pos].regex,
			value : valor,
			anchor:'95%',
			readOnly:true,
			format:'d/m/Y',
			listeners :{ beforerender:function(){									
								   if(aJSonItems[pos].id == "fi")								   
								    	this.allowBlank = false;				   
								   }
					},
			value:valor
		}};
	if (xtype == 'combo') 
			return {
					xtype:'combo',
					triggerAction:'all',
					forceSelection:true,
					editable:false,
					fieldLabel: aJSonItems[pos].fieldLabel,
					id: aJSonItems[pos].id,					
					maskRe:aJSonItems[pos].regex,
					anchor:'95%',
					displayField:'displayField',
					valueField:aJSonItems[pos].name,//'valueField',
					hiddenName:aJSonItems[pos].name,
					allowBlank:false,
					mode:'local',
					autoCreate: true,
					listeners :{beforerender:function(){									
								  if( aJSonItems[pos].valueid != "")
								   this.value=  aJSonItems[pos].valueid;
								   var n = aJSonItems[pos].name;
								 // document.body.getElementsByTagName('nom_organo').value= aJSonItems[pos].valueid;
								  //Ext.get('nom_organo').value=aJSonItems[pos].valueid;
								   /*alert(n)
								   alert(aJSonItems[pos].valueid);*/
								   }
					},
					store:new Ext.data.SimpleStore({
								fields:[aJSonItems[pos].name,'displayField'],
								data:aJSonItems[pos].data})
					
					};
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
	return store=new Ext.data.Store({
					url: aJSonGrid1[1].store.url,
                    //baseParams:aJSonGrid1[1].store.params,		
					reader:new Ext.data.JsonReader({
					    
						root:aJSonGrid1[1].store.rdRoot,
						id: aJSonGrid1[1].store.rdId,
						totalProperty:aJSonGrid1[1].store.rdTotRec },
				   		 aJSonGrid1[1].store.rdCampos				
					)
				});
}
    
	function CrearBoton_Adicionar(menu,fm,fe,nodo){
	    return [btnAdicionar=new Ext.Button({
							icon:perfil.dirImg+'adicionar.png',
							tooltip: 'Adicionar estructura',
							iconCls:'btn',
							text:'Adicionar',							
							menu:menu,		
							handler: function(){accion='Adicionar'}		  
		}),btnModificar=new Ext.Button({
							icon:perfil.dirImg+'modificar.png',
							tooltip: 'Modificar estructura',
							iconCls:'btn',
							text:'Modificar',
							listeners :{ beforerender:function(){
														if(nodo==0)
															this.disable();
										}},
							handler:function(){
						    accion='Modificar';
							fm();						
							  
							} 
		}),btnEliminar=new Ext.Button({	
						icon:perfil.dirImg+'eliminar.png',
						tooltip:'Eliminar estructura',
						iconCls:'btn',
						text:'Eliminar',
						listeners :{ beforerender:function(){
													if(nodo==0)
													this.disable();
									}},
						//disabled:true,
						handler:function(){
                         fe();						
						}
					}),btnAyuda=new Ext.Button({	
						icon:perfil.dirImg+'ayuda.png',
						tooltip:'Ayuda estructura',
						iconCls:'btn',						
						handler:function(){
                         					
						}
					})/*,'->',btOrganigrama=new Ext.Button({	
						icon:perfil.dirImg+'/organigrama/globe.gif',
						tooltip:'Ver Organigrama',
						iconCls:'btn',
						text:'Ver Organigrama',
						disabled:false,
						handler:function(){ VentanaOrganigrama('estatico')	}
					}),btOrganigramaEstatico=new Ext.Button({	
						icon:perfil.dirImg+'/organigrama/imgfolder.gif',
						tooltip:'Ver Organigrama',
						iconCls:'btn',
						text:'Ver Organigrama Estatico',
						disabled:false,
						handler:function(){ VentanaOrganigrama('dinamico')	}
					})*/];
		
	}
function Habilita(com){
	
}
function dameGridMenu(aJSonGrid1){
		var json = aJSonGrid1[2].menu;
		var menu =new Ext.menu.Menu();
		for(i=0;i<json.length;i++ )
		{
			var id1 =json[i].id;
			//alert(json[i].id);
			menu.add({
						text: json[i].text,
						id:id1,
						handler: function(){
							traerJsonAjaxForm("construirformularioinsercion",{idtabla:this.getId()});
						}
					});
		}
		return menu; 
}
/**Grid Gestion dinamica
 * @return Ext.grid.GridPanel()
 */
var store
/*function dameGrid(Json){
	store =dameStoreGrid(Json);
	var colum =dameGridColumn(Json);
	var tbar =CrearBoton_Adicionar(dameGridMenu(Json));
	gdGestionDinamica = new Ext.grid.GridPanel({
	frame:true,
	id:"grid",
	iconCls:'icon-grid',
	height:200,
	autoExpandColumn:'expandir',
	store:store,
	sm:new Ext.grid.RowSelectionModel({singleSelect:true}),
	cm:colum,
	tbar:[tbar],
	bbar: new Ext.PagingToolbar({
		pageSize: 20,
		store:store,
		displayInfo: true,
		displayMsg: 'Resultados de {0} - {1} de {2}',
		emptyMsg: "No hay resultados para mostrar."
	})
});
	//gdGestionDinamica.getStore().load();
return gdGestionDinamica;

}*/

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
/*function mostrarVCompGen(){
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
}*/
//Ventana del grid dinamico
/*function mostrarVGridGen(){
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
}*/
var formulario;
//Ventanas
//Ventana con el FormPanel


/*function traerJsonAjaxForm(url,params)
			{
				Ext.Ajax.request({
   				url: url,
   				success : function(resp,obj)
   							{
      						res=resp.responseText;
      						eval("var oRes="+res);      
      						 mostrarVCompGen(oRes)
      						 },
      						 params: params
				})
			};
*/

function getRandomNum(lbound, ubound)
{
	return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
}			

	
function cargarorgaaaa()
{

 Ext.Ajax.request({
   				url: 'mostrarorganigrama?idpadre='+nodosel.id+'&rand='+getRandomNum(1, 1000000000),
   				success : function(resp,obj)
   							{
      						res=resp.responseText;
      						eval("oRes="+res); 
      						creaOrg(oRes);   			
      						
      						 }
      						 
				})

}	

function VentanaOrganigrama(dinamico)
{

  var urlOrga;
  var Chtml=""+mytree+"";
  
  var oRes;
  switch(dinamico)
  {
   case 'estatico':
   urlOrga='mostrarorganigrama';  
      			win = new Ext.Window({                
                layout: 'fit',				
                width : 730,
                height: 530,
				autoScroll:true,
                closeAction:'hide',
				title:'Organigrama',
                plain: true,
                modal:true,
				contentEl:'organigrama',
				//items:[panelventana],
                buttons: [{
                    text     : 'Cerrar',
                    handler  : function(){
                        win.hide();
                    }
                }]
            });
       
        win.show(); 
        win.load({
				url: 'Organigrama.html',
				text: 'Cargando datos...',
				scripts: true
			});		
  
   break;
   
   case 'dinamico':
   urlOrga='mostrarorganigramaestatico';
   Chtml='<img src="http://10.12.163.159/repo/VDesarrollo/aplicaciones/erp/metadatos/index.php/estructura/mostrarorganigramaestatico?idpadre='+nodosel.id+'&rand='+getRandomNum(1, 1000000000)+'"/>';
    win = new Ext.Window({                
                layout: 'fit',
				html:Chtml,
                width : 730,
				id:'ventana',
                height: 530,
				autoScroll:true,
                closeAction:'hide',
				title:'Organigrama',
                plain: true,
                modal:true,
				//items:[],
                buttons: [{
                    text     : 'Cerrar',
                    handler  : function(){
                        win.hide();
                    }
                }]
            });
       
        win.show(); 
   break;
   
  }
  
 
  
               
       	
    

}

//-------FUNCION PARA CREAR EL ORGANIGRAMA
function creaOrg(nodosOrg){
		
	var i=0;
	var j=0;
	var bandera=0;
	
	for(i;i<nodosOrg.length;i++)
	{
		bandera=0;
		for(j=0;j<nodosOrg.length;j++)
		{
			if(nodosOrg[i].idpadre==nodosOrg[j].id && nodosOrg[i].id!=nodosOrg[j].id)
			{
				bandera=1;
				break;
			}	
		}
		
		
		if(bandera)
		{
			
			mytree.add(nodosOrg[i].id,nodosOrg[i].idpadre,nodosOrg[i].texto);
		}
		else
		{
			mytree.add(nodosOrg[i].id,-1,nodosOrg[i].texto,nodosOrg[i].texto);
		}
		
	}
	//return mytree;
	mytree.UpdateTree();
}	
	