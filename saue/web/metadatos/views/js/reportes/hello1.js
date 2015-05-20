var t = null, nodes,panelOrganigrama,paneltbar,nodoUltimoLevel,nodoprimero,nodoUltimo;
 var perfil = window.parent.UCID.portal.perfil;
 
 function cargarInterfaz()
{
Ext.onReady(function(){
    var win;
	Ext.QuickTips.init();
 tree = new Ext.tree.TreePanel({        
        useArrows:true,
        autoScroll:true,
		split:true,
	    width: 200,
	    minSize: 175,
	    maxSize: 400,
	    height:600,
	    collapsible: true,
		title:'Estructuras',
        animate:true,
        enableDD:true,
		tbar:[ boton=new Ext.Button({text:'Ver Organigrama',handler:function(){creaArray(tree.getChecked('id'),tree_comp.getChecked('id'))}})],
	    region:'west',
        containerScroll: true,
        dataUrl: 'buscarhijos',
        root: {
            nodeType: 'async',			
			icono:'img/menu/forum.gif',
            text: 'ministerios',
            draggable:false,
            id:'Estructuras'
        }
    })   
    
    tree.getRootNode().expand();
	
	
		tree.on("click",function(n){
		nodosel=n;
		AbrirTreeComposicion();
		})
	

	var Tree= Ext.tree;
	
	var loader_comp=new Tree.TreeLoader({
							dataUrl:'buscarcomposicion'						
					});
	
    /*TREE PANEL DEL ARBOL DE LAS COMPOSICIONES */
		var tree_comp = new Tree.TreePanel({        
        						autoScroll:true,								
        						split:true,
								region:'center',
								collapsible: true,
        						width: 200,
        						height:500,
        						minSize: 175,
        						maxSize: 400,   
                                title:'Estructura',	                            								
        						collapsed :false,
       							margins:'0 0 0 5',                    
        						layoutConfig:{  animate:true   },								
        						enableDD:true,        						
        						//collapseMode:'mini',
        						containerScroll: true,
        						loader: loader_comp
        						
				});
		
		/* RAIZ DEL ARBOL DE LAS COMPOSICIONES*/
		root_comp = new Tree.AsyncTreeNode({
       					text: 'Composici&oacute;n',
        				draggable:false,
        				id:'Composicion'
				});
				
		/*ASIGNANDOLE LA RAIZ AL ARBOL DE LAS COMPOSICIONES*/
		tree_comp.setRootNode(root_comp);
		
		/*MANDANDO AL ARBOL QUE SE MUESTRE*/
		tree_comp.show();
		
		/*MANDANDO AQUE SE EXPANDA EN LA RAIZ*/
		root_comp.expand();
	
	
    
	 function inicializarOrganigrama(){
        
            t = new ECOTree('t', 'hello-win');
            t.config.useTarget = false;
            t.config.colorStyle = ECOTree.CS_NODE;
            t.config.nodeFill = ECOTree.NF_FLAT;
            t.config.selectMode = ECOTree.SL_NONE;
            t.config.defaultNodeWidth = 100;
            t.config.defaultNodeHeight = 35;
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
                    tamNodo = longuitud * 25;
                
                else 
                    tamNodo = longuitud * 10;
					
		return 	tamNodo;		
        }
        
       
        function creaArray(idEstructura, idComposicion){
        
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
                var j = 0;
                var k = 0;
                var bandera = 0;
                var arrNodo = [];
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
                    
                    
                    arrNodo[i] = {
                        id: aux.attributes.id,
                        idpadre: idPadre,
                        texto: aux.attributes.text,
                        icono: perfil.dirImg+'/organigrama/'+aux.attributes.icono,
                        profundidad: aux.getDepth()
                    };
                    
                }
                
                
                
                for (j; j < cant; j++) {
                    bandera = 0;
                    for (k = 0; k < cant; k++) {
                        if (arrNodo[j].idpadre == arrNodo[k].id && arrNodo[j].id != arrNodo[k].id) {
                            bandera = 1;
                            break;
                        }
                    }
                    var tamNodo = null;
                    var tamTexto = null;
                    
                    tamTexto = arrNodo[j].texto.length
                    
                    var tamNodo = anchoNodo(tamTexto);
                    
                    if (bandera) {
                    
                        t.add(arrNodo[j].id, arrNodo[j].idpadre, arrNodo[j].texto, arrNodo[j].icono, tamNodo);
                        cont++;
                        
                    }
                    else {
                    
                        t.add(arrNodo[j].id, -1, arrNodo[j].texto, arrNodo[j].icono, tamNodo);
                        
                    }
                    
                }
                
                t.UpdateTree();
                ventana();
                
                var dim = inicializarCanvas(arrNodo);
                altocanvas = dim[0];
                anchocanvas = dim[1];
                
                altocanvasInicial = dim[0];
                anchocanvasInicial = dim[1];
                
                
                dimencionesCanvasPaneles(0);
                
                
                
                t.UpdateTree();
                
                
            }
            else 
                Ext.Msg.alert('Aviso', 'Debe seleccionar al menos un checked');
        }
	

 
/*function creaArray(idEstructura,idComposicion){  
	
	var datos=[];
	
	if(idEstructura.length>=1)
	datos=idEstructura.concat(idComposicion)
	else
	if(idComposicion.length>=1)
	datos=idComposicion.concat(idEstructura)
	
	nodes=datos;
	
	if(idEstructura.length>=1 || idComposicion.length>=1)
	{
	
	t = new ECOTree('t','hello-win');	
	t.config.useTarget=false;
	
	t.config.colorStyle = ECOTree.CS_NODE;
	t.config.nodeFill = ECOTree.NF_FLAT;
	t.config.selectMode = ECOTree.SL_NONE;
	t.config.defaultNodeWidth = 100;
	t.config.defaultNodeHeight = 35;
	t.config.iSubtreeSeparation = 50;
	t.config.iSiblingSeparation = 20;
	t.config.iLevelSeparation = 40;
				
	var i=0;
	var j=0;
	var k=0;
	var bandera=0;
	var arrNodo = [];
	var aux=null;
	var padre=-1;
	var pAnterior=null;
	var cantMayor=0;
	var cant=datos.length;
	
	var cont=0;
	var nodo = [];
	var aux='';
	
	for (i=0;i<cant;i++){
		aux=''
		aux = tree_comp.getNodeById(datos[i]) || tree.getNodeById(datos[i]) ;
		var idPadre=null;
		if(aux.attributes.id==aux.attributes.idpadre)
		{
		idPadre=-1;
		
		}
		else
		idPadre=aux.attributes.idpadre;
		
		
		arrNodo[i] = {id : aux.attributes.id, idpadre : idPadre, texto : aux.attributes.text, icono :perfil.dirImg+'/organigrama/'+aux.attributes.icono+'.gif',profundidad:aux.getDepth()};

	}
	
	
	
	for(j;j<cant;j++)
	{
		bandera=0;
		for(k=0;k<cant;k++)
		{
			if(arrNodo[j].idpadre==arrNodo[k].id && arrNodo[j].id!=arrNodo[k].id)
			{
				bandera=1;
				break;
			}	
		}
		var tamNodo=null;
		var tamTexto=null;
		tamTexto=arrNodo[j].texto.length
		
		
		if(tamTexto<=7 &&tamTexto>3 )
		 tamNodo=tamTexto*15;
		 else
		if(tamTexto<=3 && tamTexto>1) 
		tamNodo=tamTexto*30;
		else
		if(tamTexto==1)
		tamNodo=60;
		else
		tamNodo=tamTexto*10;
		
		if(bandera)
		{
		  
			t.add(arrNodo[j].id,arrNodo[j].idpadre,arrNodo[j].texto,arrNodo[j].icono,tamNodo);
			cont++;
			
		}
		else
		{
		  
			t.add(arrNodo[j].id,-1,arrNodo[j].texto,arrNodo[j].icono,tamNodo);
		
		}
		
	   }
	   
	t.UpdateTree();
	ventana();
	
	
	anchocanvas=inicializarCanvas(arrNodo);
	
	
	//canv.height=anchocanvas;
	
	
	if(anchocanvas>370)
	{
	panelOrganigrama.setHeight(anchocanvas+10);
	paneltbar.setHeight(anchocanvas+10);
	}
	
	
	t.UpdateTree();
	

	}
	 else
	 Ext.Msg.alert('Aviso', 'Debe seleccionar al menos un checked');
			}
			
      
*/
var separador=new Ext.Toolbar.Separator({});			
	

var botonArchivo=new Ext.Toolbar.Button({
            text: 'Archivo',
            icon: 'img/menu/list-items.gif',
			iconCls:'btn',
            // Menus can be built/referenced by using nested menu config objects
            menu : {
			         items: [
                        {text: 'Bold'},//, handler: onItemClick},
                        {text: 'Italic'},//, handler: onItemClick},
                        {text: 'Imprimir (Ctrl+P)'},//, handler: onItemClick}, '-',{
                        {text: 'Cerrar Ventana (Ctrl+R)'}/*, handler: onItemClick*/
						     
						
                            ]
					}
        })
		

var botonVer=new Ext.Toolbar.Button({
            text: 'Ver',
            icon: 'img/menu/menu-show.gif',
			iconCls:'btn',
            // Menus can be built/referenced by using nested menu config objects
            menu : {
			       items: [
                                {text: 'Relleno',
						             menu: {        
                                            items: [
                                                    
                                                    {
                                                         text: 'Solido',
                                                         checked: false,
                                                         group: 'relleno',
                                                         checkHandler:function(){ ChangeNodeFill(1)}
                                                    }, {
                                                           text: 'Gradiente',
                                                           checked: true,
                                                           group: 'relleno',
                                                          checkHandler: function(){ChangeNodeFill(0)}
                                                     }
                                                  ]
                }
			},
                      {text: 'Posicion',
						
						 menu: {        // <-- submenu by nested config object
                                            items: [
                                                    // stick any markup in a menu
                                                    {
                                                         text: 'Arriba->Abajo',
                                                         checked: true,
                                                         group: 'posicion',
                                                        checkHandler: function (){ChangePosition(0)}
                                                    }, {
                                                           text: 'Abajo->Arriba',
                                                           checked: false,
                                                           group: 'posicion',
                                                          checkHandler: function (){ChangePosition(1)}
                                                     },/* {
                                                           text: 'Derecha->Izquierda',
                                                           checked: false,														   
                                                           group: 'posicion',
                                                          checkHandler: function (){ChangePosition(2)}
                                                     },*/ {
                                                            text: 'Izquierda->Derecha',
                                                            checked: false,
                                                             group: 'posicion',
                                                          checkHandler: function (){ChangePosition(3)}
                                                        }
                    ]
                }
						},
                        {text: 'Lineas',
						   menu: {        
                                            items: [
                                                    
                                                    {
                                                         text: 'Rectas',
                                                         checked: true,
                                                         group: 'lineas',
                                                         checkHandler:function (){ChangeLinkType('M')}
                                                    }, {
                                                           text: 'Curvas',
                                                           checked: false,
                                                           group: 'lineas',
                                                           checkHandler:function (){ChangeLinkType('B')}
                                                     }
                                                     ]
                }
						},
						{text: 'Estilo de Colores',
						   menu: {       
                                            items: [                                                   
                                                    {
                                                         text: 'Color Niveles',
                                                         checked: false,
                                                         group: 'estilo',
                                                         checkHandler: function(){ChangeColorStyle(1)}
                                                    }, {
                                                           text: 'Color Nodo',
                                                           checked: true,
                                                           group: 'estilo',
                                                         checkHandler: function(){ChangeColorStyle(0)}
                                                     }
                    ]
                }
						},
						{text: 'Alineacion',
						   menu: {        // <-- submenu by nested config object
                                            items: [
                                                    // stick any markup in a menu
                                                    {
                                                         text: 'Arriva',
                                                         checked: true,
                                                         group: 'alineacion',
                                                         checkHandler: function(){ChangeNodeAlign(0)}
                                                    }, {
                                                           text: 'Centro',
                                                           checked: false,
                                                           group: 'alineacion',
                                                          checkHandler: function(){ChangeNodeAlign(1)}
                                                     }, {
                                                           text: 'Abajo',
                                                           checked: false,
                                                           group: 'alineacion',
                                                          checkHandler: function(){ChangeNodeAlign(2)}
                                                     }
                    ]
                }
						 },
                        {text: 'Color'/*, handler: onItemClick*/, 
						      menu: {
                                     items: [
                                            new Ext.menu.ColorItem({
											               selectHandler:function(cp, color)
											                            {
																		ChangeColors(color,cp)
																		//alert(color)
																		
                                                                  
                                                                        }
																   })
                                            ]
                                    }
						}
                            ]
					}
        })		


var botonBuscar=new Ext.Toolbar.Button({
            text: 'Buscar',
            icon: 'blist',
            // Menus can be built/referenced by using nested menu config objects
            menu : {
			         items: [
                        {text: 'Buscar por titulo'},
						{text: 'Buscar por Metadato'},
						{text: 'Buscar por ambos'}
						     
						
                            ]
					}
        })
		
var botonAyuda=new Ext.Toolbar.Button({
            text: 'Ayuda',
            icon: 'blist',
            // Menus can be built/referenced by using nested menu config objects
            menu : {
			         items: [
                        {text: 'Acerca de'},//, handler: onItemClick},
                        {text: 'Interesante'},//, handler: onItemClick},
                        {text: 'Como usar'},//, handler: onItemClick}, '-',{
                        {text: 'ayuda'}/*, handler: onItemClick*/
						     
						
                            ]
					}
        })	

var botonp=new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/Ar-Ab.gif',
			iconCls:'btn',
			tooltip:'Ver Organigrama de Arriba a Abajo',
			handler: function (){ChangePosition(0)}
        })

		
 panelOrganigrama=new Ext.Panel({
    contentEl : 'hello-win',
    width: 680,
    height: 370
})	


 paneltbar=new Ext.Panel({	
    width: 680,
    height: 370,  
frame:false,  	
    items:[panelOrganigrama]
	
})
var panelMenu=new Ext.Panel({
  tbar:[botonArchivo,botonVer,botonBuscar,botonAyuda],
  width: 750,  
  bodyBorder:false,
  border:false,
  frame:false,
  region:'north'

})
var panelTolbar=new Ext.Panel({
border:false,
bodyBorder:false,
frame:false,
  tbar:[botonp,new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/Ab-Ar.gif',
			iconCls:'btn',
			tooltip:'Ver Organigrama de Abajo a Arriba',
			handler: function (){ChangePosition(1)}
        })/*,new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/D-I.gif',
			iconCls:'btn',
			tooltip:'Ver Organigrama de Derecha a Izquierda',
			handler: function (){ChangePosition(2)}
        })*/,new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/I-D.gif',
			iconCls:'btn',
			tooltip:'Ver Organigrama de Izquierda a Derecha',
			handler: function (){ChangePosition(3)}
        }),"-",new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/solido.gif',
			iconCls:'btn',
			tooltip:'Ver nodos solidos',
			handler:function(){ ChangeNodeFill(1)}
        }),new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/degradao.gif',
			iconCls:'btn',
			tooltip:'Ver nodos con degradao',
			handler: function(){ChangeNodeFill(0)}
        }),"-",new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/recta.gif',
			iconCls:'btn',
			tooltip:'Ver linea recta',
			handler:function (){ChangeLinkType('M')}
        }),new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/curva.gif',
			iconCls:'btn',
			tooltip:'Ver linea solida',
			handler:function (){ChangeLinkType('B')}
        }),"-",new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/clevel.gif',
			iconCls:'btn',
			tooltip:'Ver color por niveles',
			handler: function(){ChangeColorStyle(1)}
        }),new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/cnodo.gif',
			iconCls:'btn',
			tooltip:'Ver color por nodos',
			handler: function(){ChangeColorStyle(0)}
        }),"-",new Ext.Toolbar.Button({           
            icon: perfil.dirImg+'/organigrama/color.gif',
			iconCls:'btn',
			tooltip:'Color de los nodos',
			menu: {
                                     items: [
                                            new Ext.menu.ColorItem({
											               selectHandler:function(cp, color)
											                            {
																		ChangeColors(color,cp)
																		
                                                                  
                                                                        }
																   })
                                            ]
                                    }
        })],
  width: 750,   
  region:'south'

})

var panelG=new Ext.Panel({
items:[panelMenu,panelTolbar]
})
	
	var viewport = new  Ext.Viewport({
				            layout:'border',
				            items:[tree,tree_comp]
				        });


AbrirTreeComposicion = function(){
				root_comp.setText(nodosel.text)
				root_comp.id=nodosel.id; 
				loader_comp.baseParams ={idestructura:nodosel.id};	
				loader_comp.load(root_comp);
				if(tree_comp.collapsed) tree_comp.expand() ;				
			}						
	
function ventana()
{
    	
        // create the window on the first click and reuse on subsequent clicks
        if(!win){
            win = new Ext.Window({
			    title:'Organigrama',								
				items:[paneltbar],
				autoScroll:true,
                tbar:[panelG],				            
                width       : 700,
                height      : 480,				
                closeAction :'hide',
                plain       : true
                
            });
        }
    win.show();
	/*win.load({
    url: "../Organigrama/simple1.htm",
    params: {param1: "foo", param2: "bar"}, 
    nocache: false,
    text: "Loading...",
    scripts: true
});*/
    }		
		
   
});

}
cargarInterfaz()

//----------------------------------FUNCIONES PARA EL MANEJO DEL ORGANIGRAMA


function inicializarCanvas(arrObjetos){
    var dimenciones = [];
    var cant = arrObjetos.length;
    var temp = 1;
    var cont = 1;
    var mayor = 0;
	var mayorLeft=0;
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
			
			var posDerecha=document.getElementById(arrObjetos[temp].id).style.left;
			posDerecha = posDerecha.substr(0, posDerecha.length - 2);
			posDerecha=parseInt(posDerecha);
			
			if(posDerecha>mayorLeft)
			 mayorLeft= posDerecha;
            
        }
    }
   // alert(mayor)
    var alto = (mayor * 35) + (mayor - 1) * 40 + 80;
    
    
    dimenciones[0] = alto;
    var ultimoId = arrObjetos[cant - 1].id;
    
   // alert(ultimoId)
    
    var posicionUltimo = document.getElementById(ultimoId).style.left;
    posicionUltimo = posicionUltimo.substr(0, posicionUltimo.length - 2)
	
	 var logNodoMayorLeft = document.getElementById(ultimoId).style.width;
    logNodoMayorLeft = logNodoMayorLeft.substr(0, logNodoMayorLeft.length - 2)
	logNodoMayorLeft=parseInt(logNodoMayorLeft)
	
    
    var ancho = mayorLeft + 50+logNodoMayorLeft;
    
   // alert(ancho)
   // alert(nodoUltimoLevel)
    dimenciones[1] = ancho;
    
    
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
                    var cordultimoLevel=document.getElementById(nodoUltimoLevel).style.left;
                    var cordUltimoNodo=document.getElementById(nodoUltimo).style.top;
                    
                   
                    cordultimoLevel=cordultimoLevel.substr(0, cordultimoLevel.length - 2);
                    cordUltimoNodo=cordUltimoNodo.substr(0, cordUltimoNodo.length - 2);
                    
                    //alert("ultimo level  "+cordultimoLevel)
                   // alert("ultimo nodo  "+cordUltimoNodo)
                    
                    altocanvas = parseInt(cordUltimoNodo)+100 ;
                    anchocanvas = parseInt(cordultimoLevel) + 200;
					
                    
                }
				else
				if(posicion == 2 ){
				
				    var corPrimerNodo=document.getElementById(nodoprimero).style.left;
                    var cordUltimoNodo=document.getElementById(nodoUltimo).style.top;
                    
                   
                    corPrimerNodo=corPrimerNodo.substr(0, corPrimerNodo.length - 2);
                    cordUltimoNodo=cordUltimoNodo.substr(0, cordUltimoNodo.length - 2);
                    
                    //alert("ultimo level  "+cordultimoLevel)
                   // alert("ultimo nodo  "+cordUltimoNodo)
                    
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
            t.config.topXAdjustment = 20;
            t.config.topYAdjustment = -20;			
            break;
        
		case ECOTree.RO_BOTTOM:
		    dimencionesCanvasPaneles(ECOTree.RO_BOTTOM);
            t.config.topXAdjustment = 20;            
            t.config.topYAdjustment = -1 * (altocanvas - 10);			
            break;
        
		case ECOTree.RO_RIGHT:         
           	t.config.iRootOrientation = 3;		
            t.config.topXAdjustment = 20;
            t.config.topYAdjustment = -20;
            t.UpdateTree();			
            dimencionesCanvasPaneles(3);
			
			t.config.topXAdjustment = 20  ;
			alert(anchocanvas)
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
			
			function ChangeLinkType(tipo) {				
				t.config.linkType = tipo;
				t.UpdateTree();
			}
			
			function ChangeNodeAlign(alineacionNodo) {
				t.config.iNodeJustification = parseInt(alineacionNodo);
				t.UpdateTree();
			}
			
			function Modify(what, inp, val) {
				var q = parseInt(document.forms[0][inp].value) + val;
				document.forms[0][inp].value = q;
				t.config[what] = q;
				t.UpdateTree();	
			}
			
			function IncreaseSubtreeSep() { Modify("iSubtreeSeparation","stsep",5); }
			function DecreaseSubtreeSep() { Modify("iSubtreeSeparation","stsep",-5); }
			function IncreaseSiblingSep() { Modify("iSiblingSeparation","sbsep",5); }
			function DecreaseSiblingSep() { Modify("iSiblingSeparation","sbsep",-5); }
			function IncreaseLevelSep() { Modify("iLevelSeparation","lvsep",5); }
			function DecreaseLevelSep() { Modify("iLevelSeparation","lvsep",-5); }
			
			function ChangeColors(colorSeleccionado,obj) {
			 
			    var constant="#15428B";
				// nodes = ['O','E',3,4,5,6,7,'eight',9,10,11,12,13,14,15];
				var c = "";
				t.config.linkColor = constant;
				
						c = "#"+colorSeleccionado;
						//c="#FFCCCC";
						
						t.config.levelColors = t.config.levelBorderColors = ["#5555FF","#8888FF","#AAAAFF",c];
						//break;
				//}			
				for (var n = 0; n < nodes.length; n++) {
					t.setNodeColors(nodes[n], c, constant, false);
				}	
				t.UpdateTree();
			}				
			
			function ChangeSearchMode() {
				var mode = parseInt(document.forms[0].searchMode.value);
				t.config.searchMode = mode;
			}
			
			function SearchTree() {
				var txt = document.forms[0].search.value;
				t.searchNodes(txt);
			}		
			
			function ChangeSelMode() {				
				var mode = parseInt(document.forms[0].selMode.value);
				t.config.selectMode = mode;				
				t.unselectAll();				
			}
			
			function ChangeNodeFill(modo) {				
				var mode =parseInt(modo);
				t.config.nodeFill = mode;				
				t.UpdateTree();				
			}
			
			function ChangeColorStyle(estylo) {				
				var mode = parseInt(estylo);
				t.config.colorStyle = mode;				
				t.UpdateTree();				
			}						
			
			function ChangeUseTarget() {				
				var flag = (document.forms[0].usetarget.value == "true");
				t.config.useTarget = flag;
				t.UpdateTree();
			}
			
			function selectedNodes() {
				var selnodes = t.getSelectedNodes();
				var s = [];
				for (var n = 0; n < selnodes.length; n++)
				{
					s.push('' + n + ': Id=' + selnodes[n].id + ', Title='+ selnodes[n].dsc + ', Metadata='+ selnodes[n].meta + '\n');
				}
				alert('The selected nodes are:\n\n' + ((selnodes.length >0) ? s.join(''): 'None.'));
			}
					