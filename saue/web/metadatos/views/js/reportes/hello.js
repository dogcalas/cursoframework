
 var perfil = window.parent.UCID.portal.perfil;

function cargarInterfaz(){
    Ext.onReady(function(){        
		var win,winOrganigramas,winResumenPersonal,winResumenMediosTecnicos,winAyuda,boton;
        Ext.QuickTips.init();
        tree = new Ext.tree.TreePanel({
            useArrows: true,
            autoScroll: true,
            split: true,
            width: 200,
            minSize: 175,
            maxSize: 400,
            height: 600,
            collapsible: true,
            title: 'Estructuras',
            animate: true,
            enableDD: true,
            tbar: [boton = new Ext.Button({
                text: 'Ver Organigrama',
				id:'btnOrganigrama',
                handler: function(){
                    creaArray(tree.getChecked('id'), tree_comp.getChecked('id'))
                }
            }),new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/recuperar.gif',
                iconCls: 'btn',
                tooltip: 'Recuperar Organigrama',
                handler: function(){
                   VentanaGridOrganigramas()
                }
            })],
            region: 'west',
            containerScroll: true,
            dataUrl: 'buscarhijos',
            root: {
                nodeType: 'async',
                icono: perfil.dirImg+'/organigrama/menu/forum.gif',
                text: 'Ministerios',
                draggable: false,
                id: "Estructuras"
            }
        })
        
        tree.getRootNode().expand();
        
        
        tree.on("click", function(n){
            nodosel = n;
            AbrirTreeComposicion();
        })
        
        
        var Tree = Ext.tree;
        
        var loader_comp = new Tree.TreeLoader({
            dataUrl: 'buscarcomposicion'
        });
        
        /*TREE PANEL DEL ARBOL DE LAS COMPOSICIONES */
        var tree_comp = new Tree.TreePanel({
            autoScroll: true,
            //split: true,
            region: 'center',
            collapsible: true,
            width: 200,
            height: 500,
            minSize: 175,
            maxSize: 400,
            title: 'Estructura',
            collapsed: true,
            margins: '0 0 0 0',
            layoutConfig: {
                animate: true
            },
            enableDD: true,
            //collapseMode:'mini',
            containerScroll: true,
            loader: loader_comp
        
        });
        
        /* RAIZ DEL ARBOL DE LAS COMPOSICIONES*/
        root_comp = new Tree.AsyncTreeNode({
            text: 'Composición',
            draggable: false,
            id: 'Composicion'
        });
        
        /*ASIGNANDOLE LA RAIZ AL ARBOL DE LAS COMPOSICIONES*/
        tree_comp.setRootNode(root_comp);
        
        /*MANDANDO AL ARBOL QUE SE MUESTRE*/
        tree_comp.show();
        
        /*MANDANDO AQUE SE EXPANDA EN LA RAIZ*/
        root_comp.expand();
        
        
        
        
        function inicializarOrganigrama(){
        
            t=null;
			t = new ECOTree('t', 'hello-win');
            t.config.useTarget = false;
            t.config.colorStyle = ECOTree.CS_NODE;
            t.config.nodeFill = ECOTree.NF_FLAT;
            t.config.selectMode = ECOTree.SL_SINGLE;
            t.config.defaultNodeWidth = 100;
            t.config.defaultNodeHeight = 40;
            t.config.iSubtreeSeparation = 50;
            t.config.iSiblingSeparation = 20;
            t.config.iLevelSeparation = 40;
            t.config.topXAdjustment = 20;
            t.config.topYAdjustment = -20;
            
        }
        
        function anchoNodo(longuitud){
           
		   var tamNodo;
		   
		    if (longuitud <= 7 && longuitud > 3) 
                tamNodo = longuitud * 15;
            else 
                if (longuitud <= 3) 
                    tamNodo = longuitud * 40;
                
                else 
                    tamNodo = longuitud * 10;
					
		return 	tamNodo;		
        }
        
       
	  
	   
        function creaArray(idEstructura, idComposicion){
        
            arrNodo=[];
			var datos = [];
            
            if (idEstructura.length >= 1) 
                datos = idEstructura.concat(idComposicion)
            else 
                if (idComposicion.length >= 1) 
                    datos = idComposicion.concat(idEstructura)
            
            nodes = datos;
            
            if (idEstructura.length >= 1 || idComposicion.length >= 1) {
            
                inicializarOrganigrama();
                
                var i = 0;  
                var aux = null;
                var padre = -1;
                var pAnterior = null;
                var cantMayor = 0;
                var cant = datos.length;
                
                var cont = 0;
                var nodo = [];
                var aux = '';
                
                for (i = 0; i < cant; i++) {
                    aux = ''
                    aux = tree_comp.getNodeById(datos[i]) || tree.getNodeById(datos[i]);
                    var idPadre = null;
                    if (aux.attributes.id == aux.attributes.idpadre) {
                        idPadre = -1;
                        
                    }
                    else 
                        idPadre = aux.attributes.idpadre; 
                        var ind=i+1;						
				     
                    arrNodo[i] = {
                        id: aux.attributes.id,
                        idpadre: idPadre,
                        texto: aux.attributes.text,
                        icono: aux.attributes.icon,//'./comun/recursos/iconos/'+ico+'.gif',//'perfil.dirImg+'/organigrama/'+aux.attributes.icon,
                        indice: ind,
						profundidad: aux.getDepth(),
						nota:"",
						tipoPintar:aux.attributes.agrupacion
                    };
                    
                }
                
                
                PintarOrganigrama(arrNodo)
                
                
              //  t.UpdateTree();
                
                
            }
            else 
                Ext.Msg.alert('Aviso', 'Debe existir al menos un nodo para chequeado');
        }
        
        
		function PintarOrganigrama(arrNodos)
		{
		  nodes=[];
		  arrNodo=[];
		  inicializarOrganigrama();
		  var j = 0;
          var k = 0;
		  var cont = 0;
		  var cant=arrNodos.length;
		  var bandera = 0; 
		  for (j; j < cant; j++) {
		            nodes[j]=arrNodos[j].id;
                    bandera = 0;
                    for (k = 0; k < cant; k++) {
                        if (arrNodos[j].idpadre == arrNodos[k].id && arrNodos[j].id != arrNodos[k].id) {
                            bandera = 1;
                            break;
                        }
                    }
                    var tamNodo = null;
                    var tamTexto = null;
                    
                    tamTexto = arrNodos[j].texto;
					
					tamTexto=tamTexto.length;
                    
                    var tamNodo = anchoNodo(tamTexto);
                    
                    if (bandera) {
                    
                        t.add(arrNodos[j].id, arrNodos[j].idpadre, arrNodos[j].texto, arrNodos[j].icono,arrNodos[j].indice,arrNodos[j].nota,arrNodos[j].tipoPintar, tamNodo);
                        cont++;
                        
                    }
                    else {
                    
                        t.add(arrNodos[j].id, -1, arrNodos[j].texto, arrNodos[j].icono,arrNodos[j].indice,arrNodos[j].nota,arrNodos[j].tipoPintar, tamNodo);
                        
                    }
                    
                }
                
                t.UpdateTree();
                ventana();
                
                var dim = inicializarCanvas(arrNodos);
                altocanvas = dim[0];
                anchocanvas = dim[1];
                
                altocanvasInicial = dim[0];
                anchocanvasInicial = dim[1];
                
                arrNodo=arrNodos;
                dimencionesCanvasPaneles(0);
                
                ChangePosition(0)
		}
		
        
         
        
        var botonArchivo = new Ext.Toolbar.Button({
            text: 'Acciones',
            icon: perfil.dirImg+'/organigrama/menu/list-items.gif',
            iconCls: 'btn',
            // Menus can be built/referenced by using nested menu config objects
            menu: {
                items: [{
                    text: 'Nuevo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+N)',
					icon: perfil.dirImg+'/organigrama/nuevo.gif',
                    iconCls: 'btn'
					// handler: onItemClick
                },
                {
                    text: 'Abrir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+A)',
					icon: perfil.dirImg+'/organigrama/recuperar.gif',
                    iconCls: 'btn'
					//, handler: onItemClick},
                },
				{
                    text: 'Guardar   (Alt+G)',
					icon: perfil.dirImg+'/organigrama/salvar.gif',
                    iconCls: 'btn'
					//, handler: onItemClick},
                },
				{
                    text: 'Resumen del Personal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+P)',
					icon: perfil.dirImg+'/organigrama/cat_users_16.png',
                    iconCls: 'btn',
					handler: resumenPersonal
                },
				{
                    text: 'Resumen Medios Tecnicos &nbsp;&nbsp; (Alt+M)',
					icon: perfil.dirImg+'/organigrama/custom_16.png',
                    iconCls: 'btn',
					handler: resumenMediosTecnicos
                },
				{
                    text: 'Agregar Notas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+S)',
					icon: perfil.dirImg+'/organigrama/notas.gif',
                    iconCls: 'btn',
					handler: selectedNodes
                },
				{
                    text: 'Collapsar Todos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+C)',
					icon: perfil.dirImg+'/organigrama/plus.gif',
                    iconCls: 'btn',
					handler: function(){ t.collapseAll();}
                },
				{
                    text: 'Expandir Todos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+E)',
					icon: perfil.dirImg+'/organigrama/less.gif',
                    iconCls: 'btn',
					handler: function(){ t.expandAll();}
                },
				{
                    text: 'Buscar Nodo (Alt+I)',
					icon: perfil.dirImg+'/organigrama/buscar.gif',
                    iconCls: 'btn',
					handler: buscarNodo
                },
                {
                    text: 'Imprimir (Alt+I)',
					icon: perfil.dirImg+'/organigrama/imprimir.gif',
                    iconCls: 'btn'
					//, handler: onItemClick
                },
                {
                    text: 'Cerrar Ventana&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Alt+R)',
					icon: perfil.dirImg+'/organigrama/delete_16.png',
                    iconCls: 'btn',
					handler: function(){win.hide()}
                }]
            }
        })
        
        
        var botonVer = new Ext.Toolbar.Button({
            text: 'Ver',
            icon: perfil.dirImg+'/organigrama/menu/menu-show.gif',
            iconCls: 'btn',
            // Menus can be built/referenced by using nested menu config objects
            menu: {
                items: [{
                    text: 'Relleno',
                    menu: {
                        items: [{
                            text: 'Sólido',
                            checked: false,
                            group: 'relleno',
                            checkHandler: function(){
                                ChangeNodeFill(1)
                            }
                        }, {
                            text: 'Gradiente',
                            checked: true,
                            group: 'relleno',
                            checkHandler: function(){
                                ChangeNodeFill(0)
                            }
                        }]
                    }
                }, {
                    text: 'Posición',
                    
                    menu: { // <-- submenu by nested config object
                        items: [ // stick any markup in a menu
                        {
                            text: 'Arriba->Abajo',
                            checked: true,
                            group: 'posicion',
                            checkHandler: function(){
                                ChangePosition(0)
                            }
                        }, {
                            text: 'Abajo->Arriba',
                            checked: false,
                            group: 'posicion',
                            checkHandler: function(){
                                ChangePosition(1)
                            }
                        }/*, {
                            text: 'Derecha->Izquierda',
                            checked: false,
                            group: 'posicion',
                            checkHandler: function(){
                                ChangePosition(2)
                            }
                        }*/, {
                            text: 'Izquierda->Derecha',
                            checked: false,
                            group: 'posicion',
                            checkHandler: function(){
                                ChangePosition(3)
                            }
                        }]
                    }
                }, {
                    text: 'Lineas',
                    menu: {
                        items: [{
                            text: 'Rectas',
                            checked: true,
                            group: 'lineas',
                            checkHandler: function(){
                                ChangeLinkType('M')
                            }
                        }, {
                            text: 'Curvas',
                            checked: false,
                            group: 'lineas',
                            checkHandler: function(){
                                ChangeLinkType('B')
                            }
                        }]
                    }
                }, {
                    text: 'Estilo de Colores',
                    menu: {
                        items: [{
                            text: 'Color Niveles',
                            checked: false,
                            group: 'estilo',
                            checkHandler: function(){
                                ChangeColorStyle(1)
                            }
                        }, {
                            text: 'Color Nodo',
                            checked: true,
                            group: 'estilo',
                            checkHandler: function(){
                                ChangeColorStyle(0)
                            }
                        }]
                    }
                }, {
                    text: 'Alineación',
                    menu: { // <-- submenu by nested config object
                        items: [ // stick any markup in a menu
                        {
                            text: 'Arriva',
                            checked: true,
                            group: 'alineacion',
                            checkHandler: function(){
                                ChangeNodeAlign(0)
                            }
                        }, {
                            text: 'Centro',
                            checked: false,
                            group: 'alineacion',
                            checkHandler: function(){
                                ChangeNodeAlign(1)
                            }
                        }, {
                            text: 'Abajo',
                            checked: false,
                            group: 'alineacion',
                            checkHandler: function(){
                                ChangeNodeAlign(2) 
                            }
                        }]
                    }
                }, {
                    text: 'Color',
					icon: perfil.dirImg+'/organigrama/color.gif',
                    iconCls: 'btn',
                    menu: {
                        items: [new Ext.menu.ColorItem({
                            selectHandler: function(cp, color){
                                ChangeColors(color, cp)
                                //alert(color)
                            
                            
                            }
                        })]
                    }
                }]
            }
        })
        
        
        var botonBuscar = new Ext.Toolbar.Button({
            text: 'Buscar',
            icon: 'blist',
            // Menus can be built/referenced by using nested menu config objects
            menu: {
                items: [{
                    text: 'Buscar por título',
					icon: perfil.dirImg+'/organigrama/buscar.gif',
                    iconCls: 'btn',
					handler: buscarNodo
                }]
            }
        })
        
        var botonAyuda = new Ext.Toolbar.Button({
            text: 'Ayuda',
            icon: 'blist',
            // Menus can be built/referenced by using nested menu config objects
            menu: {
                items: [{
                    text: 'Acerca de',
					icon:perfil.dirImg+'/organigrama/polls_16.png',
                    iconCls: 'btn',
					handler: function(){ventanaAyuda('<br><img src="img/ej orga.gif" width="292" height="197" align="top"><br><br><div align="justify">This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details</div>')}
                }]
            }
        })
        
        var botonp = new Ext.Toolbar.Button({
            icon: perfil.dirImg+'/organigrama/Ar-Ab.gif',
            iconCls: 'btn',
            tooltip: 'Ver Organigrama de arriba a abajo',
            handler: function(){
                ChangePosition(0)
            }
        })
        
        
         panelOrganigrama = new Ext.Panel({
            contentEl: 'hello-win',
            width: 680,
            height: 370
        })
        
        
         paneltbar = new Ext.Panel({
            width: 680,
            height: 370,
            frame: false,
            items: [panelOrganigrama]
        
        })
        var panelMenu = new Ext.Panel({
            tbar: [botonArchivo, botonVer, botonBuscar, botonAyuda],
            width: 750,
            bodyBorder: false,
            border: false,
            frame: false,
            region: 'north'
        
        })
        var panelTolbar = new Ext.Panel({
            border: false,
            bodyBorder: false,
            frame: false,
            tbar: [botonp,new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/Ab-Ar.gif',
                iconCls: 'btn',
	     disabled:true, 
                tooltip: 'Ver Organigrama de abajo a arriba',
                handler: function(){
                    ChangePosition(1)
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/D-I.gif',
                iconCls: 'btn',
	     disabled:true, 
                tooltip: 'Ver Organigrama de derecha a izquierda',
                handler: function(){
                    ChangePosition(2)
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/I-D.gif',
                iconCls: 'btn',
	     disabled:true, 			
                tooltip: 'Ver Organigrama de izquierda a derecha',
                handler: function(){
                    ChangePosition(3)
                }
            }),"-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/solido.gif',
                iconCls: 'btn',
                tooltip: 'Ver nodos sólidos',
                handler: function(){
                    ChangeNodeFill(1)
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/degradao.gif',
                iconCls: 'btn',
                tooltip: 'Ver nodos con degradado',
                handler: function(){
                    ChangeNodeFill(0)
                }
            }), "-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/recta.gif',
                iconCls: 'btn',
                tooltip: 'Ver línea recta',
                handler: function(){
                    ChangeLinkType('M')
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/curva.gif',
                iconCls: 'btn',
                tooltip: 'Ver línea sólida',
                handler: function(){
                    ChangeLinkType('B')
                }
            }), "-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/clevel.gif',
                iconCls: 'btn',
                tooltip: 'Ver color por niveles',
                handler: function(){
                    ChangeColorStyle(1)
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/cnodo.gif',
                iconCls: 'btn',
                tooltip: 'Ver color por nodos',
                handler: function(){
                    ChangeColorStyle(0)
                }
            }),"-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/plus.gif',
                iconCls: 'btn',
                tooltip: 'Colapsar todos',
                handler: function(){
                    t.collapseAll();
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/less.gif',
                iconCls: 'btn',
                tooltip: 'Expandir todos',
                handler: function(){
                   t.expandAll();
                }
            }), "-",new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/color.gif',
                iconCls: 'btn',
                tooltip: 'Color de los nodos',
                menu: {
                    items: [new Ext.menu.ColorItem({
                        selectHandler: function(cp, color){
                            ChangeColors(color, cp)
							PonerIconoColor(color); 
                        }
                    })]
                }
            }), "-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/salvar.gif',
                iconCls: 'btn',
				id:'btnSalvarOrganigrama',
                tooltip: 'Salvar organigrama seleccionado',
                handler: function(){                   
				   salvarOrganigrama();
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/imprimir.gif',
                iconCls: 'btn',
                tooltip: 'Imprimir organigrama',
                handler: function(){
                    
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/recuperar.gif',
                iconCls: 'btn',
				id:'btnRecuperarOrganigrama',
                tooltip: 'Recuperar organigrama',
                handler: function(){
                   VentanaGridOrganigramas()
                }
            }),"-",new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/cat_users_16.png',
                iconCls: 'btn',
				id:'btnResumenPersonal',
                tooltip: 'Resumen del personal',
                handler: function(){
				
				resumenPersonal();
                   
                }
            }),new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/custom_16.png',
                iconCls: 'btn',
				id:'btnResumenMediosTecnicos',
                tooltip: 'Resumen de medios técnicos',
                handler: function(){
				resumenMediosTecnicos();
                   
                }
            }),"-",new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/notas.gif',
                iconCls: 'btn',
				id:'btnNotas',
                tooltip: 'Adicionar notas al nodo seleccionado',
                handler: function(){
				selectedNodes();
                   
                }
            }),"-",new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/buscar.gif',
                iconCls: 'btn',
				id:'btnBuscar',
                tooltip: 'Buscar nodo',
                handler: function(){
				buscarNodo();
                   
                }
            })],
            width: 750,
            region: 'south'
        
        })
		
		function salvarOrganigrama()
		{
		   Ext.MessageBox.prompt('Salvar Organigrama', 'Entre el nombre con el que desea guardar el organigrama:', enviarOrganigrama);
		}
		
		function enviarOrganigrama(btn,text)
		{
		//console.info(arrNodo)
		 
		  if(btn=="ok")
		  {
		    if(text=="")
			{
		   Ext.MessageBox.show({
           title: 'Error',
           msg: 'Nombre del organigrama en blanco',
           buttons: Ext.MessageBox.OK,
           //animEl: 'mb9',
           //fn: showResult,
           icon: Ext.MessageBox.ERROR
           });
	        return
			}
		   
		   Ext.Ajax.request({
           url: 'guardarorganigrama',
           success: function(){
		   
		   //mostrarMensaje(action.result.codMsg,action.result.mensaje);
		   
		   },
           //failure: otherFn,        
           		   
           params: { nombre: text, nodos:Ext.encode(arrNodo)}
         });
		
		  }
		}
		
		
			var storeResumenPersonal= new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: ''
    }),
    reader: new Ext.data.JsonReader({
        totalProperty: "cant_filas",
        root: "filas",
        id: "idResumenPersoonal"
    }, [
	    {name: 'Entidad',mapping: 'entidad'}, 
		{name: 'otro',mapping: 'otro'}, 
		{name: 'Funcionales',mapping: 'funcionales'}, 
		{name: 'Directivos',mapping: 'Directivos'}, 
		{name: 'Operario Servicio',mapping: 'operarioServicio'}, 
		{name: 'Invitados',mapping: 'invitados'}, 
		{name: 'Total',mapping: 'total'}
	
	])
});
	
	var smResumenPersonal= new Ext.grid.RowSelectionModel({
    singleSelect: false
});
	
	var gridResumenPersonal=new Ext.grid.GridPanel({
    store: storeResumenPersonal,
    columns: [  
    {
        header: "Entidad",
		sortable: true,
        dataIndex: 'Entidad'
    }, {
        header: "Otro",
		sortable: true,
        dataIndex: 'otro'
    }, {
        header: "Funcionales",
		sortable: true,
        dataIndex: 'Funcionales'
    }, {
        header: "Directivos",
		sortable: true,
        dataIndex: 'Directivos'
    }, {
        header: "Operario-Servicio",
		sortable: true,
        dataIndex: 'Operario Servicio'
    }, {
        header: "Invitados",
		sortable: true,
        dataIndex: 'Invitados'
    }, {
        header: "Total",
		sortable: true,
        dataIndex: 'Total'
    }], 
    sm: smResumenPersonal,    
    height: 265,
    frame: false,
    iconCls: 'icon-grid',
    autoShow: true,    
    bodyStyle: 'text-align:center;width:600',
    loadMask: {msg:'Cargando...'},    
    autoScroll: true,
    viewConfig: {
        autoFill: true
    },    
    bbar: new Ext.PagingToolbar({
        pageSize: 10,
        store: storeResumenPersonal,
        displayInfo: true,
        displayMsg: ' Rango({0} - {1}) Total:{2}',
        emptyMsg: "No Datos"      
    })
});
	
	
	
	
	var storeGridResumenMediosTecnicos= new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: ''
    }),
    reader: new Ext.data.JsonReader({
        totalProperty: "cant_filas",
        root: "filas",
        id: "idResumenMediosTecnicos"
    }, [
	    {name: 'Denominacion',mapping: 'denominacion'}, 
		{name: 'Cantidad',mapping: 'cantidad'}
	
	])
});
	
	
	var smResumenMediosTecnicos= new Ext.grid.RowSelectionModel({
    singleSelect: false
});
	
	
	var gridResumenMediosTecnicos=new Ext.grid.GridPanel({
    store: storeGridResumenMediosTecnicos,
    columns: [  
    {
        header: "Denominación",
        dataIndex: 'Denominacion'
    }, {
        header: "Cantidad",
        dataIndex: 'Cantidad'
    }], 
    sm: smResumenMediosTecnicos,// ASIGNO EL sm CREADO ARRIBA     
    height: 165,
    frame: false,
    iconCls: 'icon-grid',
    autoShow: true,    
    bodyStyle: 'text-align:center;width:600',
    loadMask: {msg:'Cargando...'},    
    autoScroll: true,
    viewConfig: {
        autoFill: true
    },    
    bbar: new Ext.PagingToolbar({
        pageSize: 10,
        store: storeGridResumenMediosTecnicos,
        displayInfo: true,
        displayMsg: ' Rango({0} - {1}) Total:{2}',
        emptyMsg: "No Datos"      
    })
});
		
		function resumenPersonal()
		{
		   var btnsalir=Ext.get('btnResumenPersonal');
		   if (!winResumenPersonal) {
                winResumenPersonal = new Ext.Window({
                    title: 'Resumen del Personal',
                    items: [gridResumenPersonal],
                    autoScroll: true,                    
                    width: 600,
					modal:true,
                    height: 300,
                    closeAction: 'hide',
                    plain: true
                
                });
            }
            winResumenPersonal.show(btnsalir);
		
		}
		
		function resumenMediosTecnicos()
		{
		  var btnsalir=Ext.get('btnResumenMediosTecnicos');
		   if (!winResumenMediosTecnicos) {
                winResumenMediosTecnicos = new Ext.Window({
                    title: 'Resumen de Medios Técnicos',
                    items: [gridResumenMediosTecnicos],
                    autoScroll: true,
					modal:true,                    
                    width: 400,
                    height: 200,
                    closeAction: 'hide',
                    plain: true
                
                });
            }
            winResumenMediosTecnicos.show(btnsalir);
		}
		
		function PonerIconoColor(img)
		{
		 // BtnColor
		}
        
        var panelG = new Ext.Panel({
            items: [panelMenu, panelTolbar]
        })
        
        var viewport = new Ext.Viewport({
            layout: 'border',
            items: [tree, tree_comp]
        });
        
		
		//---------para lo de recuperar el organigrama
		
		var storeGridOrganigramas = new Ext.data.Store({    
                 proxy: new Ext.data.HttpProxy({url: 'mostrarorganigramas'}),
                 reader: new Ext.data.JsonReader({
                 totalProperty: "cant_filas",
                 root: "filas",
                 id: "idorganigrama"
                 },
				 [{name: 'idorganigrama',mapping: 'idorganigrama'}, 
				 {name: 'Nombre',mapping: 'Nombre'}
				 ])
            });
			
			
			
	    var btnRecuperarOrganigrama=new Ext.Button({text:'Recuperar',disabled:true,handler:RecuperarOrganigrama,icon:perfil.dirImg+'aceptar.png',iconCls:'btn'})		
		
		var btnEliminarOrganigrama=new Ext.Button({text:'Eliminar',disabled:true,handler:EliminarOrganigrama,icon:perfil.dirImg+'eliminar.png',iconCls:'btn'})	
         
        var btnModificarOrganigrama=new Ext.Button({text:'Modificar',disabled:true,handler:ModificarOrganigrama,icon:perfil.dirImg+'eliminar.png',iconCls:'btn'})		 
	    
		var modoSeleccionOrganigramas= new Ext.grid.RowSelectionModel({
                 singleSelect: true
               });
			   
			   
		modoSeleccionOrganigramas.on("selectionchange", function(_sm, indiceFila, record){
                 if(modoSeleccionOrganigramas.getSelections().length>0)
                   {
                   btnRecuperarOrganigrama.enable(); 
                   btnEliminarOrganigrama.enable();	
                   btnModificarOrganigrama.enable();					   
                  }  
                });	

  		var MenuGridOrganigrama = new Ext.menu.Menu({
							  items:[{
									text    : 'Recuperar',
									iconCls : '',									
									handler : RecuperarOrganigrama
							         },
									 {
									text    : 'Eliminar',
									iconCls : '',									
									handler : EliminarOrganigrama
							   },
							   {
									text    : 'Modificar',
									iconCls : '',									
									handler : ModificarOrganigrama
							   }]
				});	
			
			   
		var gridOrganigramas=new Ext.grid.GridPanel({
                store: storeGridOrganigramas,
                columns: [{header: "Nombre organigrama",sortable: true,dataIndex: 'Nombre'}],
                sm: modoSeleccionOrganigramas, 
                height: 290,
                frame: false,
                iconCls: 'icon-grid',
                autoShow: true,     
                bodyStyle: 'text-align:center;width:600',
                loadMask: true,
                 autoScroll: true,
                 viewConfig: {
                 autoFill: true
                  },
              tbar: [btnRecuperarOrganigrama,btnEliminarOrganigrama,btnModificarOrganigrama],
              bbar: new Ext.PagingToolbar({
              pageSize: 10,
              store: storeGridOrganigramas,
              displayInfo: true      
    }),
	         listeners:{
			       dblclick:RecuperarOrganigrama
			 
			 }  
})


    
	    gridOrganigramas.on('rowcontextmenu',function(grid,rowIndex,e){ MenuGridOrganigrama.showAt(e.getXY());})
	 
		function mostrarMensaje(tipo, msg, fn){
	    var buttons = new Array(Ext.MessageBox.OK, Ext.MessageBox.OKCANCEL, Ext.MessageBox.OK);
	    var title = new Array('Información', 'Confirmación', 'Error');
	    var icons = new Array(Ext.MessageBox.INFO, Ext.MessageBox.QUESTION, Ext.MessageBox.ERROR);
	    Ext.MessageBox.show({
	        title: title[tipo - 1],
	        msg: msg,
	        buttons: buttons[tipo - 1],
	        icon: icons[tipo - 1],
	        fn: fn
	    });
	}
		
		function ModificarOrganigrama()
		{
		  Ext.MessageBox.prompt('Modificar nombre organigrama', 'Por favor entre el nuevo nombre del organigrama:', cambiarNombreOrganigrama,'','',modoSeleccionOrganigramas.getSelected().get('Nombre'));
		}
		
		function cambiarNombreOrganigrama(btn,text)
		{
		     if(text=="" && btn!="cancel")
	         {
	       Ext.MessageBox.show({
           title: 'Error',
           msg: 'Entre el nombre del organigrama',
           buttons: Ext.MessageBox.OK,
           //animEl: 'btnNotas',
           fn: ModificarOrganigrama,
           icon: Ext.MessageBox.ERROR
                  });
	          }
	          else
               {
			if(btn=="ok" ) 
			{
		   Ext.Ajax.request({
           url: 'modificarnombreorganigrama',
		   disableCaching:true,
           success: function(){	
		   
		  
		   storeGridOrganigramas.reload();		   
		   btnRecuperarOrganigrama.disable(); 
           btnEliminarOrganigrama.disable();
		   //mostrarMensaje(action.result.codMsg,action.result.mensaje);
		   
		   },
           //failure: otherFn,        
           		   
           params: { nombre: modoSeleccionOrganigramas.getSelected().get('Nombre'), nuevoNombre:text}
         });
			   
	    }
	              }
	} 
		
		function EliminarOrganigrama()
		{
		
		 if(modoSeleccionOrganigramas.getSelections().length>=1)
		  {
		   
		    var msg='¿Está seguro que desea eliminar '+modoSeleccionOrganigramas.getSelected().get('Nombre')+'?'
	        mostrarMensaje(2, msg, function(btn){
	                    	
	                    	if (btn == 'ok') 
            					 {	
		   Ext.Ajax.request({
           url: 'eliminarorganigrama',
           success: function(resp,obj){	
		   storeGridOrganigramas.reload();		   
		   btnRecuperarOrganigrama.disable(); 
           btnEliminarOrganigrama.disable();	
		   
		   },
           //failure: otherFn,  
           params: { nombre: modoSeleccionOrganigramas.getSelected().get('Nombre')}
         });
		 
		 
		  }
		})
		}
		}
		
		function RecuperarOrganigrama()
		{
		
		  if(modoSeleccionOrganigramas.getSelections().length>=1)
		  {
		   Ext.Ajax.request({
           url: 'recuperarorganigrama',
           success: function(resp,obj){		
           
		     var res=Ext.decode(resp.responseText);
			 winOrganigramas.hide();
			 PintarOrganigrama(res)
			 
      	    //console.info(res)		   
		   //mostrarMensaje(action.result.codMsg,action.result.mensaje);
		   
		   },
           //failure: otherFn,  
           params: { nombre: modoSeleccionOrganigramas.getSelected().get('Nombre')}
         });
		  }
		 
		}
		
        function VentanaGridOrganigramas()
		{
		   var btnsalir=Ext.get('btnRecuperarOrganigrama')
		  if (!winOrganigramas) {
                winOrganigramas = new Ext.Window({
                    title: 'Organigramas existentes',
					modal:true,
                    items: [gridOrganigramas],
                    autoScroll: true,                    
                    width: 500,
                    height: 350,
                    closeAction: 'hide',
                    plain: true
                
                });
            }
            winOrganigramas.show(btnsalir);
			storeGridOrganigramas.load({params: {start: 0,limit: 10}});
			
			
		}
		
		//----------------fin de lo de recuperar organigrama
		
		
		
        AbrirTreeComposicion = function(){
            root_comp.setText(nodosel.text)
            root_comp.id = nodosel.id;
            loader_comp.baseParams = {
                idestructura: nodosel.id
            };
            loader_comp.load(root_comp);
            //if (tree_comp.collapsed) 
                tree_comp.expand();
        }
        
        function ventana(){
        
             var button = Ext.get('btnOrganigrama'); 
            if (!win) {
                win = new Ext.Window({
                    title: 'Organigrama',
                    items: [paneltbar],
                    autoScroll: true,
                    tbar: [panelG],
                    width: 700,
                    height: 480,
                    closeAction: 'hide',
                    plain: true
                
                });
            }
            win.show();
              
            
        }
		
		function ventanaAyuda(html)
		{
		     if (!winAyuda) {
                winAyuda = new Ext.Window({
                    title: 'Acerca de organigrama',                    
                    autoScroll: true,
                    html:html,					
                    width: 350,
                    height: 400,
					bodyStyle: 'text-align:center;padding:5px',
					modal:true,
                    closeAction: 'hide',
                    plain: true
                
                });
            }
            winAyuda.show();
		
		}
        
        
    });
    
}

cargarInterfaz()

//----------------------------------FUNCIONES PARA EL MANEJO DEL ORGANIGRAMA
function SetiarDimencionesCanvas(alto,ancho)
{
  var Talto=parseInt(alto);
  var TAncho=parseInt(ancho);
  document.getElementById('ECOTreecanvas').width=TAncho;
  document.getElementById('ECOTreecanvas').height=Talto;
}
posicionNumerica=function(idDom,leftTop)
{
    var temp=null;
	if(idDom)
	{
    switch(leftTop)
	{
	  case "top":
	   temp=document.getElementById(idDom).style.top;
	  break;
	  
	  case "left":
	  temp=document.getElementById(idDom).style.left;
	  break;
	  
	  case "height":
	  var temp=document.getElementById(idDom).style.height;
	  break;
	  
	  case "width":
	  temp=document.getElementById(idDom).style.width;
	  break;  
	
	}
	
	temp = temp.substr(0, temp.length - 2);
	temp=parseInt(temp);
	
	}
	return temp;
  
} 

function inicializarCanvas(arrObjetos){
    var dimenciones = [];
    var cant = arrObjetos.length;
    var temp = 1;
    var cont = 1;
    var mayor = 0;
	var mayorLeft=0;
	menorLeft=10000;
    var padreAnt = arrObjetos[0].idpadre;
    var nivelAnt = arrObjetos[0].profundidad;
    var pos;
    
    nodoprimero=arrObjetos[0].id;
    nodoUltimo=arrObjetos[cant-1].id;
	
	
    
    if (cant == 1) 
        mayor = 1;
    else {
    
        for (temp; temp < cant; temp++) {
            if ((arrObjetos[temp].idpadre != -1) && (arrObjetos[temp].profundidad != nivelAnt) && (arrObjetos[temp].profundidad >= nivelAnt)) {
                cont++;
                
                
            }
            else 
                if (arrObjetos[temp].idpadre == -1) {
                    cont = 1;
                    
                }
            if (cont > mayor) {
                mayor = cont;
                nodoUltimoLevel=arrObjetos[temp].id;
            }
            padreAnt = arrObjetos[temp].idpadre;
            nivelAnt = arrObjetos[temp].profundidad;
			
			var posDerecha=posicionNumerica(arrObjetos[temp].id,"left")			
			var posIzquierda=posicionNumerica(arrObjetos[temp].id,"left");
			
			
			if(posDerecha>mayorLeft)
			 mayorLeft= posDerecha;
			 
			 if(posIzquierda<menorLeft )
			 {
			 menorLeft= posIzquierda;
			 idmenor=arrObjetos[temp].id;
			 }
            
        }
    }
   // alert(mayor)
    var alto = (mayor * 40) + (mayor - 1) * 40 + 80;
    
    
    dimenciones[0] = alto;
    var ultimoId = arrObjetos[cant - 1].id;
    
   
    
    var posicionUltimo = posicionNumerica(ultimoId,"left");
    
	
	var logNodoMayorLeft = posicionNumerica(ultimoId,"width");
   
    
	if(menorLeft<0)
    var ancho = mayorLeft +(-1*menorLeft)+ 300+logNodoMayorLeft;
	else
	var ancho = mayorLeft +menorLeft+ 300+logNodoMayorLeft;
	
    
  
    dimenciones[1] = ancho;
   // alert(idmenor)
    
    return dimenciones
}

 function dimencionesCanvasPaneles(posicion){
        
            if (posicion == 0 || posicion == 1) {
            
                altocanvas = altocanvasInicial;
                anchocanvas = anchocanvasInicial;          
                
                
            }
            else {
                if ( posicion == 3) {
                	//alert(nodoUltimoLevel)
                    var cordultimoLevel=posicionNumerica(nodoUltimoLevel,"left");
                    var cordUltimoNodo=posicionNumerica(nodoUltimo,"top");
                    altocanvas = parseInt(cordUltimoNodo)+100 ;
                    anchocanvas = parseInt(cordultimoLevel) + 200;
					
                    
                }
				else
				if(posicion == 2 ){
				
				    var corPrimerNodo=posicionNumerica(nodoprimero,"left");
                    var cordUltimoNodo=posicionNumerica(nodoUltimo,"top");
                    
                    altocanvas = parseInt(cordUltimoNodo)+100 ;
                    anchocanvas = parseInt(corPrimerNodo) + 200;
				
				}
            }
            
            if (altocanvas > 370) {
                panelOrganigrama.setHeight(altocanvas + 10);
                paneltbar.setHeight(altocanvas + 10);
            }
            else
            {
             panelOrganigrama.setHeight(370);
             paneltbar.setHeight(370);
            }
            
            if (anchocanvas > 680) {
                panelOrganigrama.setWidth(anchocanvas + 10);
                paneltbar.setWidth(anchocanvas + 10);
            }
            else
            {
            panelOrganigrama.setWidth(680);
            paneltbar.setWidth(680);
            	
            }
            
        }
        

function ChangePosition(posicion){
    var pos = parseInt(posicion);
    t.config.iRootOrientation = pos;
    switch (pos) {
       
	    case ECOTree.RO_TOP:
		    dimencionesCanvasPaneles(ECOTree.RO_TOP);
            var poss;
			if(menorLeft<0)
			poss=50+(-1*menorLeft);
			else
			poss=50;
			t.config.topXAdjustment = poss;
            t.config.topYAdjustment = -20;			
            break;
        
		case ECOTree.RO_BOTTOM:
		    dimencionesCanvasPaneles(ECOTree.RO_BOTTOM);
            var poss;
			if(menorLeft<0)
			poss=50+(-1*menorLeft);
			else
			poss=50;
			t.config.topXAdjustment = poss;           
            t.config.topYAdjustment = -1 * (altocanvas - 10);			
            break;
        
		case ECOTree.RO_RIGHT:         
           	t.config.iRootOrientation = 3;		
            t.config.topXAdjustment = 20;
            t.config.topYAdjustment = -20;
            t.UpdateTree();			
            dimencionesCanvasPaneles(3);
			
			t.config.topXAdjustment = 20  ;
			//alert(anchocanvas)
            t.config.topYAdjustment = 500//-1* (anchocanvas-50);
            t.UpdateTree();
            dimencionesCanvasPaneles(ECOTree.RO_RIGHT);
			
            break;
			
        case ECOTree.RO_LEFT:
		   
            t.config.topXAdjustment = 20;
            t.config.topYAdjustment = -20;
            t.UpdateTree();
            dimencionesCanvasPaneles(ECOTree.RO_LEFT);
            break;
    }
    t.UpdateTree();
}

function ChangeLinkType(tipo){
    t.config.linkType = tipo;
    t.UpdateTree();
}

function ChangeNodeAlign(alineacionNodo){
    t.config.iNodeJustification = parseInt(alineacionNodo);
    t.UpdateTree();
}

function Modify(what, inp, val){
    var q = parseInt(document.forms[0][inp].value) + val;
    document.forms[0][inp].value = q;
    t.config[what] = q;
    t.UpdateTree();
}

function IncreaseSubtreeSep(){
    Modify("iSubtreeSeparation", "stsep", 5);
}

function DecreaseSubtreeSep(){
    Modify("iSubtreeSeparation", "stsep", -5);
}

function IncreaseSiblingSep(){
    Modify("iSiblingSeparation", "sbsep", 5);
}

function DecreaseSiblingSep(){
    Modify("iSiblingSeparation", "sbsep", -5);
}

function IncreaseLevelSep(){
    Modify("iLevelSeparation", "lvsep", 5);
}

function DecreaseLevelSep(){
    Modify("iLevelSeparation", "lvsep", -5);
}

function ChangeColors(colorSeleccionado, obj){

    var constant = "#15428B";
    // nodes = ['O','E',3,4,5,6,7,'eight',9,10,11,12,13,14,15];
    var c = "";
    t.config.linkColor = constant;
    
    c = "#" + colorSeleccionado;
    //c="#FFCCCC";
    
    t.config.levelColors = t.config.levelBorderColors = ["#5555FF", "#8888FF", "#AAAAFF", c];
    //break;
    //}			
    for (var n = 0; n < nodes.length; n++) {
        t.setNodeColors(nodes[n], c, constant, false);
    }
    t.UpdateTree();
}

function ChangeSearchMode(){
    //var mode = parseInt(document.forms[0].searchMode.value);
    t.config.searchMode = 0;
}

function SearchTree(btn, text){
   // var txt = document.forms[0].search.value;
    if(text=="" && btn!="cancel")
	{
	       Ext.MessageBox.show({
           title: 'Error',
           msg: 'Entre el nombre del nodo a buscar',
           buttons: Ext.MessageBox.OK,
           //animEl: 'btnNotas',
           fn: buscarNodo,
           icon: Ext.MessageBox.ERROR
       });
	}
	else
    t.searchNodes(text);
}

function ChangeSelMode(){
    var mode = parseInt(document.forms[0].selMode.value);
    t.config.selectMode = mode;
    t.unselectAll();
}

function ChangeNodeFill(modo){
    var mode = parseInt(modo);
    t.config.nodeFill = mode;
    t.UpdateTree();
}

function ChangeColorStyle(estylo){
    var mode = parseInt(estylo);
    t.config.colorStyle = mode;
    t.UpdateTree();
}

function ChangeUseTarget(){
    var flag = (document.forms[0].usetarget.value == "true");
    t.config.useTarget = flag;
    t.UpdateTree();
}

function selectedNodes(){
    var selnodes = t.getSelectedNodes();
    if(selnodes.length==0)
	{
	       Ext.MessageBox.show({
           title: 'Error',
           msg: 'Por favor seleccione el nodo al que le desea agregar nota',
           buttons: Ext.MessageBox.OK,
           animEl: 'btnNotas',
           //fn: showResult,
           icon: Ext.MessageBox.ERROR
       });
	}
	else
	if(selnodes.length>0)
	{
	       Ext.MessageBox.show({
           title: 'Agregar nota a '+selnodes[0].dsc,
           msg: 'Por favor entre la nota aquí:',
           width:300,
           buttons: Ext.MessageBox.OKCANCEL,
           multiline: true,
           fn: agregarNotas,
           animEl: 'btnNotas'
       });
	}
	
	/*var s = [];
    for (var n = 0; n < selnodes.length; n++) {
        s.push('' + n + ': Id=' + selnodes[n].id + ', Title=' + selnodes[n].dsc + ', Metadata=' + selnodes[n].meta + '\n');
    }
    alert('The selected nodes are:\n\n' + ((selnodes.length > 0) ? s.join('') : 'None.'));*/
}

function buscarNodo(btn)
{
  
  Ext.MessageBox.prompt('Nodo a Buscar', 'Por favor entre el nombre del nodo a buscar:', SearchTree);
}
function agregarNotas(btn,text)
{
  var nodoSeleccionado=t.getSelectedNodes();
  var ind=nodoSeleccionado[0].indice;
  ind=parseInt(ind);
  ind=ind-1;
  arrNodo[ind].nota="";
  arrNodo[ind].nota=text;
  console.info(arrNodo)
 
  
  
 // alert(text)
}

function PonerTollTip(texto,idNodo,icon,nota,indice)
{
    var ObjToltip=null;
   if (texto && texto!="" && idNodo && idNodo!="" && icon && icon!="" && arrNodo[indice-1].nota && arrNodo[indice-1].nota!="")
   {
     ObjToltip=new Ext.ToolTip({
	 	
		target: idNodo,
        title: '<img src="'+icon+'" width="10" height="10" align="left" style="padding-right:2px" />'+'Notas de '+texto,
        autoHeight:true,
		autoWidth:true,
		titleCollapse:true,
		animCollapse:true,
        html: arrNodo[indice-1].nota,
        trackMouse:true
		
		
	 })
   }   
}
