// 1.
var typo,selccUltimo,panel=null;
var perfil = window.parent.UCID.portal.perfil;
// 2.
//UCID.portal.cargarEtiquetas('gestionarnivel', perfil.idioma, function(){cargarInterfaz();});
// 3. Inicializo el singlenton QuickTips
var tipo_Cargo,GestionarFuncionCargos,fm_Cargos;
Ext.MessageBox.buttonText.yes = "Si";
Ext.MessageBox.buttonText.ok = "Aceptar";
Ext.QuickTips.init();

var CrearForma,panel,gd_MostrarCargos,fm_Cargos,gestc,WinTreeDPA,WinTreeEspecialidad,nodoop,grid,Gestionar,st_MostrarCargos,gdGestionDinamica;

function cargarInterfaz()
{
	Ext.onReady(function()
	{
		var json;       
		var Tree = Ext.tree;
		var sufix,nodosel;
		var idop, record,store,textomenu;
		var arreglo = [];
		var children = [];
		var MenuArbol=null;
		var MenuArbolComp=null;
		
		
		selecModeGrid=new Ext.grid.RowSelectionModel({singleSelect:true});
		
/********PRIMER ARBOL, ES EL QUE CONTIENE LAS ESTRUCTURAS*************/
	
		
		/* TREE LOADER DEL ARBOL DE ESTRUCTURAS */ 		
		 loaderEstructura=new Tree.TreeLoader({
							dataUrl:'buscarhijos',
							baseParams:[{tipo:typo}]
							
					});
					
		//gestc = new GestionarCargos();
		
		var GestAgrup = new Ext.Button({
									text    : 'Gestionar agrupaci&oacute;n',
									iconCls : 'btn',
									icon:perfil.dirImg+'abrirventana.png',
									handler : function(item) {
											OnGestAgrupClick();
										}
							   });
		
		/*TREE PANEL DEL ARBOL DE ESTRUCTURAS*/
		 var tree = new Tree.TreePanel({        
	        			autoScroll:true,
						enableDD:true,
						ddAppendOnly:false, 
						dropConfig: {
							appendOnly:false
						},
						title:'Estrcuturas',
	        			split:true,
	        			width:400,
	        			minSize: 175,
	        			maxSize: 400,
	        			height:600,
	        			collapsible: true,
	       				margins:'0 0 0 2',                    
	        			layoutConfig:{animate:true   },
						region:'west',
						//tbar:[GestAgrup],
	        			//collapseMode:'mini',
	        			containerScroll: true, 
	        			loader: loaderEstructura//,	        			listeners:{dblclick:Ondbclick_Tree}
			});
			
		/* RAIZ DEL ARBOL DE ESTRUCTURAS*/
		root = new Tree.AsyncTreeNode({
       					text: 'Estructuras',
        				draggable:false,
        				id:'Estructuras'
					});
		/*ASIGNANDOLE LA RAIZ AL ARBOL*/			
		tree.setRootNode(root);
		
		/*MANDANDO AL ARBOL QUE SE MUESTRE*/
		tree.show();
		
		/*MANDANDO AQUE SE EXPANDA EN LA RAIZ*/
		root.expand();
/**SEGUNDO ARBOL, ES EL QUE CONTIENE LAS COMPOSICIONES DE LAS ESTRUCTURAS **/
		
		/* TREE LOADER DEL ARBOL DE LAS COMPOSICIONES */ 		
		var loader_comp=new Tree.TreeLoader({
							applyLoader:false,
							dataUrl:'buscarcomposicion'						
					});
		var bt_GestCargos = new Ext.Button( {
									text    : 'Gestionar cargo',
									iconCls : 'btn',
									icon:perfil.dirImg+'estructura.png',
									disabled:true,
									handler : function(item) {
											
											var uri="http://10.12.171.3:5800/report_server.php/report_viewer/index";
                      var iframe=new Ext.UCID.WinIframe({id:"parentIframe",url:uri,params:{module:"EC"}});
                      iframe.show();
											
											
										}
							   });
		/*TREE PANEL DEL ARBOL DE LAS COMPOSICIONES */
		var tree_comp = new Tree.TreePanel({        
        						autoScroll:true,
        						rootVisible:false,
								title:'Composición',
        						split:true,
        						width: 200,
        						height:500,
        						minSize: 175,
        						maxSize: 400,
        						//collapsible: true,
        						collapsed :true,
       							margins:'0 0 0 0',                    
        						layoutConfig:{animate:true},
        						//dropConfig: {appendOnly:false},
        						enableDD:true,
        						//rootVisible: false,
        						region:'center',
        						collapseMode:'mini',
        						containerScroll: true, 
        						loader: loader_comp//,
        						//tbar : [bt_GestCargos]//,        						listeners:{dblclick:Ondbclick_Tree}
				});
		
		root_comp = new Tree.AsyncTreeNode({
				       					text: 'Composición',
				        				draggable:false,
				        				id:'Composicion'
							});
				
							/*ASIGNANDOLE LA RAIZ AL ARBOL DE LAS COMPOSICIONES*/
		tree_comp.setRootNode(root_comp);
		
		root_comp.expand(false,false);
		
		/**----------- funciones para el rabajo con la subordinacion---------*/
		 

		var winDatos,nodoDesde,nodoHasta;
		var padre;
		var arbol = null;
		var node_estruct; 
		tree.on('startdrag',function(T,N){
		padre =N.parentNode;
		})
        tree.on("nodedrop", function(de){
        	arbol = 1;
          nodoHasta=de.target.id;
          nodoDesde=de.dropNode.id;
          node_estruct = de.target;
          var NEW_NODE ={
          	id:nodoDesde,
          	text:de.dropNode.text,
          	tipo:de.dropNode.attributes.tipo,
          	icon:de.dropNode.attributes.icon,
          	NomFilaestruc:de.dropNode.attributes.NomFilaestruc,
          	leaf:false
          	}
          node_estruct.removeChild(de.dropNode);
          padre.appendChild(NEW_NODE) ;
          
          mostrarVentana();
        })
        
         var padre_comp;
         var node_comp;
		tree_comp.on('startdrag',function(T,N){
		padre_comp =N.parentNode;
		})
        tree_comp.on("nodedrop", function(de){
        	arbol = 2;
          nodoHasta=de.target.id;
          nodoDesde=de.dropNode.id;
          node_comp = de.target;
          var NEW_NODE ={
          	id:nodoDesde,
          	text:de.dropNode.text,
          	tipo:de.dropNode.attributes.tipo,
          	icon:de.dropNode.attributes.icon,
          	NomFilaestruc:de.dropNode.attributes.NomFilaestruc,
          	leaf:false
          	}
          node_comp.removeChild(de.dropNode);
          padre.appendChild(NEW_NODE) ;
          
          mostrarVentana();
        })

       var Datos=[['1', 'Si'],['0', 'No']];
		var st_bool = new Ext.data.SimpleStore({
           		fields: ['excluir','denoma'],
            	data : Datos
		});

        var storeNivelVisibiliad= new Ext.data.Store({
                  url: 'mostarnivelsubordinacion',    
                  reader: new Ext.data.JsonReader({
                      totalProperty: "cant",
                      root: "datos",
                      id: "idnomsubordinacion"   
                      },[{
							 name: 'idnomsubordinacion'},{
							 name: 'denominacion'}]
                      )
                 }
         );
		storeNivelVisibiliad.load();
                          
		/*var formularioDat=new Ext.FormPanel({                           
		 					frame:true,       
		 					bodyStyle:'padding:5px 5px 0',
		 					labelWidth:120,
		 					labelAlign :'top',
   		 					autoWidth: true,
   		 					defaults: {width: 170},
   		 					items: [{
               					xtype:'combo',
              	 				fieldLabel: 'Tipo de subordinación',
               					//id:"idnomsubordinacion1",
               					editable :false,
               					//allowBlank:false,                                                       
               					triggerAction:'all',
               					forceSelection:true,
				               emptyText:'Seleccione nivel..',
				               hideLabel:false,               
				               mode: 'local',
				               forceSelection: true,               
				               store:storeNivelVisibiliad,
				               displayField:'denominacion',
				               valueField:'idnomsubordinacion',
				               hiddenName:'idnomsubordinacion'
   		 					},{
				               xtype:'combo',
				               fieldLabel: 'Eliminar subordinación anterior',
				               //id:"idbooleano",
				               editable :false,
				               //allowBlank:false,
				               triggerAction:'all',
				               forceSelection:true,
				               emptyText:'Seleccione ..',                                                                        
				               hideLabel:false,
				               autoCreate: true,
				               mode: 'local',
				               forceSelection: true,                                                   
				               store:st_bool,               
				               displayField:'denoma',
				               valueField:'excluir',
				               hiddenName:'excluir'             
   		 					}]
		});
*/
                          

            function mostrarVentana(){
             if (!winDatos) {
            	winDatos = new Ext.Window({
                title: 'Datos',
                layout:'fit',
                items: [{
					xtype:"form",
					layout:"absolute",
					x:304,
					y:80,
					frame:true,
					bodyStyle:"background-image: url(grid_10.png);",
					items:[{
					    xtype:"label",
					    text:"Tipo subordinación",
					    x:"5",
					    y:"5"
					  },{
					    xtype:"combo",
					    x:6,
					    y:21,
					    width:171,
					    emptyText:'Seleccione ..',                                                                        
				        autoCreate: true,
				        mode: 'local',
				        forceSelection: true,
				        editable:false,
					    store:storeNivelVisibiliad,
					    displayField:"denominacion",
					    hiddenName:"idnomsubordinacion",
					    valueField:"idnomsubordinacion"
					  },{
					    xtype:"combo",
					    x:6,
					    y:69,
					    width:171,
					    emptyText:'Seleccione ..',                                                                        
				        autoCreate: true,
				        editable:false,
				        mode: 'local',
				        forceSelection: true,
					    store:st_bool,
					    displayField:"denoma",
					    hiddenName:"excluir",
					    valueField:"excluir"
					  },{
					    xtype:"label",
					    text:"Eliminar la subordinación anterior",
					    x:7,
					    y:51
					  }]
					}],
                autoScroll: true,
                modal:true,                   
                width: 250,
                height: 200,
                closeAction: 'hide',
                plain: true,
                buttons: [{
                	text:'Enviar',
					handler:function(){
						if(winDatos.items.first().form.isValid()){
							winDatos.items.first().form.submit({
								params:{idDesde:nodoDesde, idHasta:nodoHasta},
								url:'cambiarsubordinacion',
								waitMsg:'Salvando datos...',
				                failure: function (form, action) {
				               
				                },
				                success:function(form, action) {
				                    if(arbol == 1){
				                    	loaderEstructura.load(node_estruct)
				                    	if(form.getValues().excluir == 1)
				                    		loaderEstructura.load(padre);
				                    }
				                    else if(arbol == 2){
				                    	loader_comp.load(node_comp)
				                    	if(form.getValues().excluir == 1)
				                    		loader_comp.load(padre_comp)
				                    }
				                }//nombre del método para registrar los datos enviados (idDesde,idHasta,idbool,idnivel)
						 	});
                            winDatos.hide();
                            
                         }
					}
		            },{
		                text     : 'Cerrar',
		                handler  : function(){winDatos.hide();}
		            }]
            	});

        }
        winDatos.items.first().form.reset();
        winDatos.show();

        }
		
		/* --------------------------------------------------------------  */
		
		/**PANEL DEL CENTRO DEL PANEL QUE ESTA DENTRO DE OTRO PANEL**/
		 panel = new Ext.Panel({
						region:'center',      
	        			height:350,
						disabled:true,
	       				width:400,
	        			layout:"fit",
	        			title:'Estructuras'
	    		});
    	
    	/**PANEL DEL CENTRO DEL VIEWPORT 
		 * 
		 */
		var panel_centro =  new Ext.Panel({
								region:'center',      
			        			height:350,
			       				width:400,
			        			layout:"border",
			        			items:[tree_comp]//<--,panel
			    		});	
				
//		var gestAgroup = new GestionarAgrupacion();

		var viewport = new  Ext.Viewport({
				            layout:'border',
				            items:[tree,tree_comp]
				        });
       	   
/**FUNCIONES de los componentes**/ 
				        
	 function cloneNode(node)
	 {
	 	var texto=node.text;
	 	var id=node.id;
	 	
	 	var a={text:texto,id:id}
	 	
	 	return a;
	 	
	 }
		var newNode=[];	
	 	/*tree.on("click",function(n){
	 		            bt_GestCargos.disable();
		                selccUltimo="estructura";
		                padre = n.parentNode;
	 					//panel.remove("grid",true);
	 					var nodo = n.getDepth() ;
	 					textomenu = n.text;
	 					sufix="";
	 					idop="";
	 					nodosel=n;
	 					panel.hide();
	 					if(selecModeGrid.hasSelection()) selecModeGrid.clearSelections();
						//traerJsonAjaxGrid("construircamposgrid",{idtabla:n.id,tipo:n.attributes.tipo},nodo)
						//cargarorgaaaa()
						
						//traeJsonAjaxMenu("construirmenu",{idtabla:n.id})
	 					var a =cloneNode(n);	 					
		                for(i=0;i<newNode.length;i++){
		                	if(newNode[i].id== n.id){		                		
		                		
		                		return;
		                	
		                	}
		                		
		                }
		                newNode.push(a);
		                AbrirTreeComposicion([a]);
						
	 			});*/
		tree.on("expandnode",function(n){							
							typo=n.attributes.tipo
				});	
		
		
		
		
		
		/*tree.on('contextmenu',function(n,e){		
		accion="Adicionar";
		sufix="";
	 	idop="";
	 	nodosel=n;
		selccUltimo="estructura";
		textomenu = n.text;
		var contextMenu;
		Ext.Ajax.request({
   						 url: 'construircamposgrid ',
   						 success : function(resp,obj)
   							{
   							var res=resp.responseText;
      						eval("var oRes="+res);  
      						 MenuArbol=oRes ;                             
                             contextMenu = new Ext.menu.Menu({
							  items:[{
									text    : 'Adicionar',
									iconCls : '',
									menu:dameGridMenu(MenuArbol),
									scope   : this
							   },{
								   text: 'Modificar',
								   handler:function(){accion='Modificar'; mod();} 
							   },{ 
							   	  text: 'Eliminar',
                                  handler:function(){accion='Modificar'; elim();}
							  }]
				});
				contextMenu.showAt(e.getXY());                 							  
      			},
      			params: {idtabla:n.id,tipo:n.attributes.tipo}
				})
			if(!Encontrado(n.id))
			//	arreglo.push(n.id)	
				{
			   arreglo = Ext.encode(arreglo)
			   nsl = n;
			}
		});*/
		//mike
		
		//tree.
		
		tree_comp.on('contextmenu',function(nodo,evento){
		   sufix="op";
		   accion="Adicionar";
		   idop=nodo.id;
		   nodoop=nodo;
		   selccUltimo="composicion";
		   textomenu=nodo.text;
		   var contextMenu;
		   if(idop.charAt(0)!="c" && nodoop.getDepth()>0){				
		   Ext.Ajax.request({
		   		url:'construircamposgridop',
		   		success: function(resp,obj)	{
   							var res=resp.responseText;
      						eval("var oRes="+res);  
      						 MenuArbolComp=oRes ;                             
                             contextMenu = new Ext.menu.Menu({
							  items:[{
									text    : 'Adicionar',
									iconCls : 'btn',
									menu:dameGridMenu(MenuArbolComp)
							   },{
							     
									text    : 'Adicionar cargo',																  
									scope   : this,
									menu:{ 
								          items:[{
												text: 'Militar',
												scope :this,
												id:'idmilitar',
												 handler:function(){
												 	var militar = new gestionarCargo();
												 	var param ={idop:nodo.id};
												 	militar.SetTitle('Adicionar cargo militar a '+nodoop.text);
												 	actionA = function(){loader_comp.load(nodo.parentNode);};
												 	militar.Adicionar('militar',param,actionA);
						                         }
											},{
												text: 'Civil',
												scope :this	,
												id:'idcivil',
												 handler:function(){
												    var civil = new gestionarCargo();
												    civil.id=nodoop.id;	 
												    var param ={idop:nodo.id};	
												    civil.SetTitle('Adicionar cargo civil a '+nodoop.text);
												    actionA = function(){loader_comp.load(nodo.parentNode);};
												 	civil.Adicionar('civil',param,actionA);

						                         } 
											}]
									}
							   },{
							      text:'Gestionar medio',
								  handler:function()
								  {
								  var med =new GestionarMedios();
								  med.id=idop;
								  med.title_WinGestionarTecnica = 'Gestionar técnicas de '+nodo.text;
								  med.mostrarWin_GestTecnicas();
									
								  }
								  
							   },{
							   text: 'Modificar',
							   handler:function(){
						                accion='Modificar';
							             mod();
							} 
							   },{ 
							   text: 'Eliminar',
                                 handler:function(){
						                accion='Eliminar';
							            elim();						
							  
							}
							   }
							  
							   ]
		   	
		   });
		   contextMenu.showAt(evento.getXY());
							 
                              							  
      						 },
      						 params: {idtabla:nodo.id,tipo:nodo.attributes.tipo,SUFIX:sufix,idestructura:nodosel.id}
				})
			}
			/**
			PARA CUANDO SE DE CILC DERECHO SOBRE EL NODO PADRE
			*/
			else if(idop.charAt(0)!="c" && nodoop.getDepth()==0){
					  params = {idtabla:nodo.id,tipo:nodo.attributes.tipo,SUFIX:sufix,idestructura:nodosel.id};
				      Ext.Ajax.request({
				   				url:'construircamposgridop',
				   				success:function(resp,obj){
						   				 var res=resp.responseText;
						      			 eval("var oRes="+res);  
						      			 var MenuArbolComp=oRes ;
						                 var contextMenu = new Ext.menu.Menu({
													  items:[{
															text    : 'Adicionar',
															iconCls : 'btn',
															menu:dameGridMenu(MenuArbolComp)
													   }]
								   			});
								   		contextMenu.showAt(evento.getXY());
		      					},params:params	});
			}
			else if(idop.charAt(0)=="c" )
				{
				
				  contextMenu = new Ext.menu.Menu({
							  items:[{
									text    : 'Modificar',
									iconCls : '',									
									scope   : this,
									handler:function(){											
										var Tipocargo = nodo.attributes.tipocargo;
										var cargo = new gestionarCargo();
										var idcargo =idop.substring(1);										
										var param = {idcargo:idcargo,tipocargo:Tipocargo};	
										actionM = function(){loader_comp.load(nodo.parentNode);};
										cargo.SetTitle('Modificar cargo '+ Tipocargo +' a '+nodo.text);
										cargo.Modificar(param,actionM);										
									}
							  },{
									text    : 'Eliminar',
									iconCls : '',									
									scope   : this,
									handler:function(){
										var cargo = new gestionarCargo();
										var idcargo =idop.substring(1);										
										var param = {idcargo:idcargo};
										actionE = function(){loader_comp.load(nodo.parentNode);};
										cargo.Eliminar(param,actionM);
										
									}
							  }]
				})
							   
							   contextMenu.showAt(evento.getXY());
				
				}
				
				
		
		});
		
		function OnSuccessGridOp(resp,obj){
   				 var res=resp.responseText;
      			 eval("var oRes="+res);  
      			 var MenuArbolComp=oRes ;
                 var contextMenu = new Ext.menu.Menu({
							  items:[{
									text    : 'Adicionar',
									iconCls : 'btn',
									menu:dameGridMenu(MenuArbolComp)
							   }]
		   			});
		   		contextMenu.showAt(evento.getXY());
      	};
      						 
		function Encontrado(nodeid){
			for(i in arreglo){
				if(arreglo[i] == nodeid)return true;
			}
			return false;
			
		};
		
		
		
		/*tree_comp.on("click",function(n,e){		       
		        selccUltimo="composicion";
				sufix="op";				
				if(n.getDepth()!=0)
				bt_GestCargos.enable();
				else
				if(n.getDepth() ==0)
				bt_GestCargos.disable();
				
				nodoop = n;
				
				var nodo = n.getDepth() ;
				textomenu = n.text;
				idop = n.id;
				
				panel.remove("grid",false);
				panel.remove("id_gd_MostrarCargos",false)
				if(gd_MostrarCargos)
				panel.remove(gd_MostrarCargos,false)
				
				
				if(idop.charAt(0)!="c")
				{				
				if(selecModeGrid.hasSelection()) selecModeGrid.clearSelections();
				traerJsonAjaxGrid("construircamposgridop",{idtabla:n.id,tipo:n.attributes.tipo,SUFIX:sufix,idestructura:nodosel.id},nodo)
				}
				else
				if(idop.charAt(0)=="c")
				{
				
				 MostrarGridCargos();
				  
				}

			});*/
		
		AbrirTreeComposicion = function(newNode){
				/*root_comp.setText(nodosel.text)
				root_comp.id=nodosel.id; */
				loader_comp.baseParams ={idestructura:nodosel.id};	
				//loader_comp.load(root_comp);
				if(tree_comp.collapsed) tree_comp.expand() ;
				
				 root_comp.appendChild(newNode);
			}
			
		
		OnGestAgrupClick = function(){
			gestAgroup.mostrarWin_GestionarAgrupaciones();
		}
		/*function Ondbclick_Tree(n,e){
						var rn = new Tree.AsyncTreeNode({
				       					text: 'lolo',
				        				draggable:false,
				        				id:'fff',
				        				children:nomencladores
							});
						tree_comp.setRootNode(rn);
						tree_comp.doLayout();
							
				//p = {idestructura:nsl.id, node:nsl.id};
           		//traerComposicion('buscarcomposicion',p);
				if(tree_comp.collapsed) tree_comp.expand() ;
			/*loader_comp.baseParams= {nodesid:arreglo}
			loader_comp.load(root_comp);
			if(tree_comp.collapsed) tree_comp.expand() ;
           }*/

		
		function dameGridMenu(aJSonGrid1){	       
			/*	var json = aJSonGrid1[2].menu;
				var menu =new Ext.menu.Menu();
				 		for(i=0;i<json.length;i++ )
				 			{
								menu.add({
										text: json[i].text,
										id:json[i].id,
										handler: function(){
												textomenu =this.text;
												var temp=this.text.toLowerCase();												
												textomenu=temp;
												idtabla = this.getId();
												traerJsonAjaxForm("construirformularioinsercion",{idtabla:this.getId(),SUFIX:sufix});
														
												}
									});
						}			
			return menu;*/ 
			}
//---modo de seleccion del grid	

selecModeGrid.on('rowselect',function(sm, row, rec)
{
  Chk_BotonGestionarEstructuras();
  textomenu = selecModeGrid.getSelected().get('abreviatura')
  record =rec;

})
var modificarNodoFilaGrid;
		
		
		
		
		
		
		mod = function(){		               
					if(selecModeGrid.getSelections().length>0)
					  {
					  modificarNodoFilaGrid="fila";
					  filaSelecGrid=selecModeGrid.getSelected().get('idestructura');
					  traerJsonAjaxForm("construirformulariomodificar",{idfila:selecModeGrid.getSelected().get('idestructura'),SUFIX:sufix});
					  }
					   else
					    if(selecModeGrid.getSelections().length<=0)
						  {
						     
						        modificarNodoFilaGrid="nodo";
							   idnodo=null;
  							   if(selccUltimo=="estructura")
							   idnodo=nodosel.id
							   if(selccUltimo=="composicion")
							    idnodo=nodoop.id
								
								
						    
 							 traerJsonAjaxForm("construirformulariomodificar",{idfila:idnodo,SUFIX:sufix});
						  }
						  
						  selecModeGrid.clearSelections();
					}
					
		elim=function(){
		        
				if(bt_GestCargos) bt_GestCargos.disable();
				var idelim; 
                 var eliminar;				 
				
				if(  selecModeGrid.getSelections().length>0)
				  {
				    eliminarNodoFilaGrid="fila";
					eliminar=selecModeGrid.getSelected().get('denominacion');
					idelim=selecModeGrid.getSelected().get('idestructura');
				  }
				      else
			           {
				       if( selecModeGrid.getSelections().length<=0)
				        {
				           eliminarNodoFilaGrid="nodo";
					       idnodo=null;
  					         if(selccUltimo=="estructura")
					            {
				                  idnodo=nodosel.id;
					              textoNodo=nodosel.text;
					             }
					         if(selccUltimo=="composicion")
					             {
					              idnodo=nodoop.id;
					               textoNodo=nodoop.text;
					              }
					         eliminar=textoNodo;
					 
					         idelim=idnodo;
				       }
			     }
		
		       var msg='¿Está seguro que desea eliminar '+eliminar+'?'
	                    mostrarMensaje(2, msg, function(btn){
	                    	
	                    	if (btn == 'ok') 
            					 {  
            					 	Ext.Ajax.request({
            					 	waitTitle:'Por favor espere...',
									waitMsg:'Eliminando datos...',
                                    url: 'eliminarvalores',
                                    success: function(){
									          
											  if(store)
											  store.reload()
									          
											  var padreSeleccionado;
											  
											  
											  
											  if(nodosel && selccUltimo=="estructura")
											  padreSeleccionado=nodosel.parentNode;
											  else
									          if(nodoop && selccUltimo=="composicion")
											  padreSeleccionado=nodoop.parentNode;
											  
											 
											  
                                              loaderEstructura.load(padreSeleccionado,function(){padreSeleccionado.expand();})
											  loader_comp.load(padreSeleccionado,function(){padreSeleccionado.expand();})
											            },
                                    
									failure: function(){},    
									 
                                    params: { idfila:idelim,SUFIX:sufix}
                              })
            					 }
            					 }
	 
	 
	                        );
	                        selecModeGrid.clearSelections();
	                        Chk_BotonGestionarEstructuras();
                     }		
		function dameGrid(Json,nodo){
				store =dameStoreGrid(Json);
				var colum =dameGridColumn(Json);
				
				var tbar =CrearBoton_Adicionar(dameGridMenu(Json),mod,elim,nodo);
				
				gdGestionDinamica = new Ext.grid.GridPanel({
										frame:true,
										id:"grid",
										maskDisabled:true,
										iconCls:'icon-grid',
										height:200,
										autoExpandColumn:'expandir',
										store:store,
										sm:selecModeGrid,
										cm:colum,
										tbar:tbar,
										loadMask:{msg :'Cargando..'},
										viewConfig: {forceFit: true },
										bbar: new Ext.PagingToolbar({
													pageSize: 20,
													store:store,
													displayInfo: true
										})
									});
					store.load({params: {start: 0,limit: 20}});		
			return gdGestionDinamica;
				}
				
	   function traerComposicion(url,param){
				Ext.Ajax.request({
   						 url: url,
   						 success : function(resp,obj)
   							{
   							var res=resp.responseText;
      						eval("var oRes="+res);  
      						oRes = nomencladores;
							children = oRes;
							//console.info(oRes);
							tree_comp.doLayout();
							
      						/*var newNode = new Ext.tree.TreeNode({id:node.id,text:node.text});
      						newNode.appendChild(oRes);
           					nodoApend.appendChild(newNode);*/
           						
      						 },
      						 params: param
				})
			};
    	function traerJsonAjaxGrid(url,param,nodo)
			{
				Ext.Ajax.request({
   				url: url,
   				success : function(resp,obj)
   							{
      						res=resp.responseText;
      						eval("var oRes="+res);      
      						//var longitud=oRes.length
      						//console.info(oRes)
      						grid = dameGrid(oRes,nodo);
							panel.remove(gd_MostrarCargos,false);
      						panel.add(grid);
							panel.doLayout();
							panel.enable();
							
							//grid.getStore().load();
      						 },
      						 params: param
				})
	};
		function mostrarVCompGen(aJSonItems){
			Chk_BotonGestionarEstructuras();
				urlForm=aJSonItems[1].url;
				var items = creaArrayItems(aJSonItems);
				
				formulario= new Ext.FormPanel({
							labelAlign: 'top',
							bodyStyle:'padding:5px 5px 0',
							autoHeight:true,
							frame:true,
							items:{layout:'column',items:items}
						});
					vCompGen = new Ext.Window({
						title:'Componentes generados',
						layout:'fit',
						width:560,
						autoHeight:true,
						modal:true,
						height:200,
						//closeAction:'hide',
						items:formulario,
						buttons:[{
									text:'Cancelar',
									icon:perfil.dirImg+'cancelar.png',
									iconCls:'btn',
									handler:function(){vCompGen.destroy();}
								},{
									text:'Aplicar',
									icon:perfil.dirImg+'aplicar.png',
									iconCls:'btn',
									handler:function(){adicionarDatos('apl')}									
								},{	
									text:'Aceptar',
									icon:perfil.dirImg+'aceptar.png',
									iconCls:'btn',
									handler:function(){
										
										adicionarDatos();
										
									}
						}]
					});
				vCompGen.setTitle(accion +" "+ textomenu)
				vCompGen.show(this);
				document.getElementsByName('nom_organo').value = 5;
		} 
	/*
	 * ,render :function(){
								   		Ext.get('nom_organo').value;
								   		alert(document.getElementsByName('nom_organo').value);
								   		//= aJSonItems[pos].valueid;
								   		alert(aJSonItems[pos].valueid);
								   }
	 */
		function traerJsonAjaxForm(url,params)
			{
				Ext.Ajax.request({
   				url: url,
   				success : function(resp,obj)
   							{
      						res=resp.responseText;
      						eval("var oRes="+res);      
      						 mostrarVCompGen(oRes);
      						 },
      						 params: params
				})
			};
	
	
		function eliminarAEstruct(){
				 var msg="Esta Seguro que desea eliminar '"+selecModeGrid.getSelected().get('denominacion')+"'"
				 mostrarMensaje(2, msg, function(){ })			
			}
	
		function Chk_BotonGestionarEstructuras()
			{
		 	if(selecModeGrid ){
			     if(selecModeGrid.hasSelection())
				 {
		 		btnModificar.enable();
   				btnEliminar.enable();
				}

		 	}
		 	else{
		 		if(btnModificar)
				btnModificar.disable();
				if(btnEliminar)
   				btnEliminar.disable();
			}
		}
		
		function adicionarDatos(apl)
				{ 
				
				
				    var fechaini=null;
					var fechafin=null;
										
					if(Ext.getCmp('fi').getValue()!="") 
					  fechaini = Ext.getCmp('fi').getValue().format('Ymd');
										
					if(Ext.getCmp('ff').getValue()!="")   
					   fechafin =  Ext.getCmp('ff').getValue().format('Ymd');
				                        
					if((fechafin!=null && fechafin < fechaini) )
						{
						mostrarMensaje('3','Fecha de inicio mayor que la fecha fin.');
						return;
						}
						else
						{
						
						
					
					if (formulario.getForm().isValid())
					{
						var parametros;
						switch(accion)
						{
						 case "Adicionar":
						 parametros={idpadre:nodosel.id,idtabla:idtabla,SUFIX:sufix,idop:idop,iddpa:idLugar,idespecialidad:idesp};					
						 break;
						  case "Modificar":
						  if(modificarNodoFilaGrid=="nodo")
						  idfilaOidnodo=idnodo;
						   if(modificarNodoFilaGrid=="fila")
						  idfilaOidnodo=filaSelecGrid;
						  parametros={idfila:idfilaOidnodo,SUFIX:sufix,idop:idop,iddpa:idLugar,idespecialidad:idesp};
						 break;
						
						}					
					formulario.getForm().submit({
						url:urlForm,
						params:parametros,
						waitTitle:'Por favor espere...',
						waitMsg:'Enviando datos...',
						failure: function(form, action){
									mostrarMensaje(action.result.codMsg,action.result.mensaje);
									if(action.result.codMsg != 3){}
						},
						success :function(form, action){
						 			mostrarMensaje(action.result.codMsg,action.result.mensaje);
						 			if(!apl) 
						 				{
										 vCompGen.destroy();
										 
										 }
						 			    else
										  {
										   var form=formulario.getForm();										 
										   if(accion=="Adicionar")
										   form.reset();                                           									   
										   }
										   
										   
						 			switch(accion)
								      {
								          case "Adicionar":
								          recargarNodoEstructura();
								          //recargarNodoEstructuraOp();										  
								          break;
								          case "Modificar":
										  switch(selccUltimo)
										  {
										    case "estructura":
											recargarNodoEstructura();
											break;
											
											case "composicion":
											recargarNodoEstructuraOp();
											break;
										  
										  }					          
								          break;
								
								      }
								      selecModeGrid.clearSelections();
								      Chk_BotonGestionarEstructuras()
									  store.reload();
						 }
					});
					 //recargarNodoEstructura();
				}
				else{ 
					mostrarMensaje(3,'Por favor verifique nuevamente que hay campo(s) con valor(es) incorrecto(s).');
					//Ext.MessageBox.show({title:'Alerta',msg: 'Los campos no pueden estar en Blanco'})
				}
				}
				
				
			}
		var loaderDPA=new Tree.TreeLoader({
							dataUrl:'hijosdpa'
							
							
					});
		var treeDPA = new Tree.TreePanel({        
	        			autoScroll:true,					
	        			width: 200,
	        			minSize: 175,
	        			maxSize: 400,
	        			height:600,	        			
	       				margins:'0 0 0 2',                    
	        			layoutConfig:{  animate:true   },
						region:'west',						
	        			enableDD:false,	        			
	        			containerScroll: true, 
	        			loader: loaderDPA,
                        listeners:{
						      click:function(n)
							  {							   
								var comp=Ext.getCmp('iddpa1');
							    comp.setValue(n.text);
								idLugar=n.id;
								WinTreeDPA.hide();
							  
							  }
						
						}						
	        			
			});
   //----------------Arbol de DPA--------------------			
	    var rootDPA = new Tree.AsyncTreeNode({
       					text: 'DPA',
        				draggable:false,
        				id:'iddpa'
					});
				
		treeDPA.setRootNode(rootDPA);
		
		
		treeDPA.show();
		
		
		root.expand();

	
		function WinFnTreeDPA()
	{
	  
	   
		   if (!WinTreeDPA) {
                WinTreeDPA = new Ext.Window({
						                    title: 'DPA',
						                    layout:'fit',
						                    items: [treeDPA],                                        
						                    width: 300,
											modal:true,
						                    height: 400,
						                    closeAction: 'hide',
						                    plain: true
						                
						                });
            }
            WinTreeDPA.show();
	
	}	
			
        abrirTree=function(){
		 
		WinFnTreeDPA();
		
		}
		
	//--------------------Arbol de especialidad----------------
	var loaderEspecialidad=new Tree.TreeLoader({
							dataUrl:'hijoespecialidad'
							
							
					});
		var treeEspecialidad = new Tree.TreePanel({        
	        			autoScroll:true,					
	        			width: 200,
	        			minSize: 175,
	        			maxSize: 400,
	        			height:600,	        			
	       				margins:'0 0 0 2',                    
	        			layoutConfig:{  animate:true   },
						region:'west',						
	        			enableDD:false,	        			
	        			containerScroll: true, 
	        			loader: loaderEspecialidad,
                        listeners:{
						      click:function(n)
							  {							   
								var comp=Ext.getCmp('idespecialidad1');
							    comp.setValue(n.text);
								idesp=n.id;
								WinTreeEspecialidad.hide();
							  
							  }
						
						}						
	        			
			});
			
	    var rootEspecialidad = new Tree.AsyncTreeNode({
       					text: 'Especialidad',
        				draggable:false,
        				id:'idespecialidad'
					});
				
		treeEspecialidad.setRootNode(rootEspecialidad);
		
		
		treeEspecialidad.show();
		
		
		root.expand();

	
		function WinFnTreeEspecialidad(){
	         
	   
		   if (!WinTreeEspecialidad) {
		   	
                WinTreeEspecialidad = new Ext.Window({
						                    title: 'Especialidad',
						                    layout:'fit',
						                    items: [treeEspecialidad],                                        
						                    width: 300,
											modal:true,
						                    height: 400,
						                    closeAction: 'hide',
						                    plain: true
						                
						                });
            }
            WinTreeEspecialidad.show();
	
	}	
			
        abrirTreeEspecialidad=function(){
		 
		WinFnTreeEspecialidad();
		
		}		
		
		function recargarNodoEstructura(){ 
               	
		      	loaderEstructura.load(nodosel,function(){nodosel.expand();})
			}
		function recargarNodoEstructuraOp(){      	
		      	loader_comp.load(root_comp,function(){root_comp.expand();})
			}
		MostrarCargos = function(){
				gestc.id =nodoop.id;
				gestc.titulo_W="Definir cargos del &aacute;rea "+ nodoop.text;
				gestc.mostrarWin_GestCargo();
				;
			}
			
			
			
		MostrarGridCargos=function(){
           // gestc.id =nodoop.id;
			var gestc = new gestionarCargo();
		   var temp=nodoop.parentNode;		  
		   gestc.wnGestionarCargoTitle = temp.text;
		   temp=temp.id;		 
		   gestc.id = temp;		   
		   gestc.mostrarWin_gestionarCargo();		   
           panel.doLayout();
        }	   

	});
}	
cargarInterfaz();
	
	
	// Ventana auxiliar de mensajes
	function mostrarMensaje(tipo, msg, fn){
	    var buttons = new Array(Ext.MessageBox.OK, Ext.MessageBox.OKCANCEL, Ext.MessageBox.OK);
	    var title = new Array('Informaci&oacute;n', 'Confirmaci&oacute;n', 'Error');
	    var icons = new Array(Ext.MessageBox.INFO, Ext.MessageBox.QUESTION, Ext.MessageBox.ERROR);
	    Ext.MessageBox.show({
	        title: title[tipo - 1],
	        msg: msg,
	        buttons: buttons[tipo - 1],
	        icon: icons[tipo - 1],
	        fn: fn
	    });
	
	
	
	
	
	
	
	
	}

	
	
