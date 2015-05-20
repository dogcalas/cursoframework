
	var perfil = window.parent.UCID.portal.perfil;
	UCID.portal.cargarEtiquetas('gestcompartimentacionsistema', function(){cargarInterfaz();});

	////------------ Inicializo el singlenton QuickTips ------------////
	Ext.QuickTips.init();
	
	////------------ Declarar variables ------------////
	var arbolAcc, arbolDominios, iddominioselec = 0, aBandera = false, banderaClick = true,arregloDeschequeados = [], arrayPadresEliminar = [], NodosInicialesChekeados = [], arrayTiene = [] ,ultimonodomarcado = 0,valor = 0, bandera = 0, iguales = 0, iddominio = 0;
	var arraySistEliminar = [], arrayFuncEliminar = [], arrayAccEliminar = [], arrayPadres = [];
	
	
	
	////------------ Funcion Cargar Interfaz ------------////
        
	function cargarInterfaz() {	
            ////------------ Botones ------------////
	btnguardarcambios = new Ext.Button({
        disabled:false,
        icon:perfil.dirImg+'aceptar.png',
        iconCls:'btn',
        text:perfil.etiquetas.lbBtnGuardar
        });
		////------------ Arbol Acciones ------------////
		arbolAccLoader = new Ext.tree.TreeLoader({
			dataUrl:'cargarSistFuncAcc',                        
			listeners:{
				'beforeload':function(atreeloader, anode) {
					iddominio = atreeloader.baseParams.iddominio;
					atreeloader.baseParams = {};
					atreeloader.baseParams.iddominio = iddominio;
					if (anode.attributes.idfuncionalidad)
						atreeloader.baseParams.idfuncionalidad = anode.attributes.idfuncionalidad;
					else if(anode.attributes.idsistema)
						atreeloader.baseParams.idsistema = anode.attributes.idsistema;
            	}
			}
		});
			
		arbolAcc = new Ext.tree.TreePanel({
			title:perfil.etiquetas.lbTitArbolSistemas,
			collapsible:true,
			autoScroll:true,
			region:'east',
			disabled:true,
			split:true,
			width:'37%',
			loader: arbolAccLoader,
                        bbar:['->',btnguardarcambios]
		});
		
		////------------ Crear nodo padre del arbol ------------////
		padreArbolAcc = new Ext.tree.AsyncTreeNode({
			text:perfil.etiquetas.lbRootNodeArbolSubsist,
			expandable:false,
			id:'0'
		});
			      
		arbolAcc.setRootNode(padreArbolAcc);
		
		arbolAcc.on('checkchange', function (node, e) {
			arbolAcc.suspendEvents();
			updateArrayDeschequeados(node);
			if (node.attributes.checked)
				marcarPadre(node.parentNode, true);
			marcarHijos(node, node.attributes.checked);
			arbolAcc.resumeEvents();
		}, this);
		
		function updateArrayDeschequeados (node) {
			var esta = -1;
			var idaccion = node.attributes.idaccion;
			var idfuncionalidad = node.attributes.idfuncionalidad;
			var idsistema = node.attributes.idsistema;
			if (idaccion) {
				if(node.attributes.checked) {
					esta = estaEnDeschequeados(arrayAccEliminar, idaccion);
					if(esta != -1)
						eliminarEnDeschequeados(arrayAccEliminar, esta);
				} else adicionarEnDeschequeados(arrayAccEliminar, idaccion);
			} else if (idfuncionalidad) {
				if(node.attributes.checked) {
					esta = estaEnDeschequeados(arrayFuncEliminar, idfuncionalidad);
					if(esta != -1)
						eliminarEnDeschequeados(arrayFuncEliminar, esta);
					esta = estaEnDeschequeados(arrayPadresEliminar, idfuncionalidad)
					if(!node.isLeaf() && esta != -1)
						eliminarEnDeschequeados(arrayPadresEliminar, esta);
					if(!node.isLeaf() && node.childNodes.length == 0)
            			arrayPadres.push(idfuncionalidad);
				} else {
					adicionarEnDeschequeados(arrayFuncEliminar, idfuncionalidad);
					if(!node.isLeaf() && node.childNodes.length == 0)
						adicionarEnDeschequeados(arrayPadresEliminar, idfuncionalidad);
					var pos = estaEnDeschequeados(arrayPadres, idfuncionalidad)
					if(!node.isLeaf() && node.childNodes.length == 0 && pos != -1)
            			arrayPadres.splice(pos,1);
				}
			} else if (idsistema) {
				if(node.attributes.checked) {
					esta = estaEnDeschequeados(arraySistEliminar, idsistema);
					if(esta != -1)
						eliminarEnDeschequeados(arraySistEliminar, esta);
				} else adicionarEnDeschequeados(arraySistEliminar, idsistema);
			}
		}

		function marcarPadre(nodo, check) {
			if(nodo && nodo.attributes.id != 0) {
				if(nodo.attributes.checked != check)
					cambiarEstadoCheck(nodo, check);
				marcarPadre(nodo.parentNode, check);
			}
		}
		
		function estaEnDeschequeados(arreglonodos, idnodo) {
			var cantidad = arreglonodos.length;
			for (p=0; p<cantidad; p++) {
				if(arreglonodos[p] == idnodo)
					return p;
			}
			return -1;
		}
			
		function marcarHijos(nodo,check) {
			nodo.eachChild(
				function(anodehijo) {
					if(anodehijo.attributes.checked != check)
						cambiarEstadoCheck(anodehijo,check);
					if(anodehijo.childNodes.length > 0)
						marcarHijos(anodehijo, check);
	            },this);
		}
		
			
		/////------------ Arbol de dominios ------------////
		arbolDominios = new Ext.tree.TreePanel({
			autoScroll:true,
			region:'center',
			split:true,
			width:'37%',
			loader: new Ext.tree.TreeLoader({
				dataUrl:'cargarArbolDominios'
			}),
			listeners:{
				'click': function(anode, e) {
					if(anode.attributes.iddominio != iddominioselec && iddominioselec != 0){
						iddominioselec = anode.attributes.iddominio;
						asignarSistFuncAccDominio(null, 0);	
					}
					else
						iddominioselec = anode.attributes.iddominio;
					arbolAccLoader.baseParams = {};
					arbolAccLoader.baseParams.iddominio = anode.attributes.iddominio;
					arbolAcc.getRootNode().reload();
					arbolAcc.enable();
				}
			}
		});
		////------------ Crear nodo padre del arbol ------------////
		padreArbolDominios= new Ext.tree.AsyncTreeNode({
			text:perfil.etiquetas.lbRootNodeArbolDominios,
			expandable:false,
			expanded:true,
			id:'0'
		});         
	
		arbolDominios.setRootNode(padreArbolDominios);
		    
		btnguardarcambios.on('click',function(){
			asignarSistFuncAccDominio(null, 0);
			//arbolDominios.disable();
			//arbolDominios.getRootNode().reload();
			//btnguardarcambios.disable();
		});
	
		////------------ Panel con los componentes de roles y entidades ------------////
		panelUsuariosDominios = new Ext.Panel({
			layout:'border',
			region:'center',
                        title:perfil.etiquetas.lbTitPanelTit,
			items:[arbolAcc,arbolDominios]
			//bbar:[btnguardarcambios]
		});
	    
		////------------ Viewport ------------////
		viewport = new Ext.Viewport({
			layout:'border',
			items:[panelUsuariosDominios]
		});
		
		function existIdInArray(arrayIdTotal, id) {
			for (var i = 0; i < arrayIdTotal.length; i++) {
				if (arrayIdTotal[i] == id)
					return true;
			}
			return false;
		}
		
		function addArrayIdNodo(arrayIdTotal, arrayIdNodo) {
	    	for (var i = 0; i < arrayIdNodo.length; i++) {
	    		var id = arrayIdNodo[i];  
	    		if (id && !existIdInArray(arrayIdTotal, id))
	    			arrayIdTotal.push(id);
	    	}
	    	return arrayIdTotal;
	    }
		
		////------------- Funciones -------------////
		function asignarSistFuncAccDominio(apl, record) {
			if (iddominio) {
				var arrayNodos = arbolAcc.getChecked();
			    var arraySistemas = [];
			    var arrayFuncionalidades = [];
			    var arrayAcciones = [];
			    var arrayId = [];
			    
			    for (var i=0; i<arrayNodos.length; i++){
			    	arraySistemas = addArrayIdNodo(arraySistemas, arrayNodos[i].getPath('idsistema').split('/'));
			    	arrayFuncionalidades = addArrayIdNodo(arrayFuncionalidades, arrayNodos[i].getPath('idfuncionalidad').split('/'));
			    	arrayId = addArrayIdNodo(arrayId, arrayNodos[i].getPath('id').split('/'));
			    	arrayAcciones = addArrayIdNodo(arrayAcciones, arrayNodos[i].getPath('idaccion').split('/'));
			    }
        		/*alert(arraySistemas);
			    alert(arrayFuncionalidades);
		    	alert(arrayAcciones);
		    	alert(arrayPadres);
			    return;*/
                Ext.getBody().mask(perfil.etiquetas.TitleWaitMsg);
                Ext.Ajax.request({
                	url: 'insertarCompartimentacionSistFuncAcc',
                	method:'POST',
                	params:{
                		arraySistemas: Ext.encode(arraySistemas),
                		arrayFuncionalidades: Ext.encode(arrayFuncionalidades),
                		arrayAcciones: Ext.encode(arrayAcciones),
                		arrayPadres:Ext.encode(arrayPadres),
                		arrayPadresEliminar:Ext.encode(arrayPadresEliminar),
                		arraySistEliminar:Ext.encode(arraySistEliminar),
                		arrayFuncEliminar:Ext.encode(arrayFuncEliminar),
                		arrayAccEliminar:Ext.encode(arrayAccEliminar),
                		iddominio:iddominio
                	},
                	callback: function (options,success,response) {
                		Ext.getBody().unmask();
                		arrayPadresEliminar = [];
                		responseData = Ext.decode(response.responseText);
                    }
                });
        	}
        }
		
		////------------ Funciones Auxiliares ------------////
		function estaEnDeschequeados(arreglonodos, idnodo) {
			var cantidad = arreglonodos.length;
			for (p=0; p<cantidad; p++) {
				if(arreglonodos[p] == idnodo)
					return p;
			}
			return -1;
		}
	        
		function eliminarEnDeschequeados(arreglo, pos) {
			arreglo.splice(pos,1);
		}
		
		function adicionarEnDeschequeados(arreglo, id) {
			if(estaEnDeschequeados(arreglo, id) == -1)
				arreglo.push(id);
		}
		
		////------------ Auxiliar para marcar y desmarcar nodos ------------////
		function cambiarEstadoCheck(anodehijo,check) {
			if(anodehijo.attributes.checked != check) {
				anodehijo.getUI().toggleCheck(check);
				anodehijo.attributes.checked = check;
				updateArrayDeschequeados(anodehijo);
			}
		}
		
		function buscarNodosTiene(nodo) {
			nodo.eachChild(
				function(anodehijo) {
					if(!anodehijo.attributes.checked && !anodehijo.isLeaf() && anodehijo.attributes.tiene && anodehijo.childNodes.length == 0)
						arrayTiene.push(anodehijo.attributes.id);
					if(anodehijo.childNodes.length > 0)
						buscarNodosTiene(anodehijo);
				},this
			);
		}
		
		function marcarArriba(nodo) {
			if(nodo.attributes.id != 0) {
				//if(estadoTodosHijos(nodo.parentNode, true))
					cambiarEstadoCheck(nodo.parentNode,true);
				marcarArriba(nodo.parentNode);
			}
		}
		
		function desmarcarArriba(nodo) {
			/*aBandera = true;
			if(nodo.attributes.id != 0) {
				cambiarEstadoCheck(nodo.parentNode,false);
				if(nodo.parentNode.attributes.id >= 0)
					desmarcarArriba(nodo.parentNode);
			}*/
		}
		
		function marcarHijos(nodo,check) {
			nodo.eachChild(
				function(anodehijo) {
					if(anodehijo.attributes.checked != check)
						cambiarEstadoCheck(anodehijo,check);
					if(anodehijo.childNodes.length > 0)
						marcarHijos(anodehijo, check);
				},this
			);
		}
		
		function estadoTodosHijos(nodo, opcion) {
            resultado = true;
            nodo.eachChild(
            	function(anodehijo) {
            		if(anodehijo.attributes.checked != opcion)
            			resultado = false;    
            	},this
            );
            return resultado;
        }
	}
