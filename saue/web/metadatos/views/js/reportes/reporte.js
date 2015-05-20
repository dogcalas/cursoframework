var perfil = window.parent.UCID.portal.perfil;
var textNotas;
var array_reporte = new Array();
var v_urlreporte;
UCID.portal.cargarAcciones(window.parent.idFuncionalidad, function(){
        array_reporte= UCID.portal.getAccionesReportes();

        });


function cargarInterfaz(){
    Ext.onReady(function(){        
		var IdReporteSel,win,winDatosOrganigrama,winOrganigramas,winResumenPersonal,winaddNotas,winResumenMediosTecnicos,formularioDatOrganigrama,winAyuda,boton,tituloOrganigrama,winCrearReporte;
        Ext.QuickTips.init();
       

      var panelSuperior=new Ext.Panel({
		region:'north',
        bodyBorder:false,
        lauyot:'fit',
		border:false,		
		 tbar: [boton = new Ext.Button({
                text: 'Reportes',
				id:'btnOrganigrama',
				menu: {
                items: [/*{
                    text: 'Organigrama',
					icon: perfil.dirImg+'/organigrama/nuevo.gif',//'../../comun/recursos/iconos/organigrama/nuevo.gif',
                    iconCls: 'btn',
					menu:{
					items:[
					      {
						    text:'Ver organigrama',
							handler: function(){                   
					winEntrarNombreOrganigrama()
					}				
							
						   },
						   {
						     text:'Recuperar organigrama',
							  handler: function(){
				                   VentanaGridOrganigramas()
				                }
							 }
							]
						}
                              },*/{
                    text: 'Todos ',
					//icon: '../../comun/recursos/iconos/organigrama/nuevo.gif',
                    iconCls: 'btn',
					menu:{
					items:[
					      {
						    text:'Plantilla de cargos',
							handler: function(){                   
									IdReporteSel='5';
									WinFnarbolReportes()
								}				
							
						   },
						   {
						     text:'Resumen de cargo por grupo de complejidad y categor&iacute;a ocupacional ',
							  handler: function(){
									IdReporteSel='2';
									WinFnarbolReportes()
				                }
							 },
							 {
						     text:'Resumen de cargos por &aacute;rea y categor&iacute;a ocupacional',
							  handler: function(){
				                   IdReporteSel='6';
								   WinFnarbolReportes()
				                }
							 },
							 {
						     text:'Relaci&oacute;n de registros de las entidades por agrupaci&oacute;n',
							  handler: function(){
				                   IdReporteSel='9';
								   WinFnarbolReportes()
				                }
							 },
							 {
						     text:'Calificador de cargos t&eacute;cnicos comunes',
							  handler: function(){
				                   IdReporteSel='3';
								   FnWinReporteCalificador()
				                }
							 },
							 {
						     text:'Relaci&oacute;n de la Localizaci&oacute;n de las Entidades por nivel estructural',
							  handler: function(){
				                   IdReporteSel='10';
								   WinFnarbolReportes()
				                }
							 },
							 {
						     text:'Relaci&oacute;n de Localizaci&oacute;n de las unidades por Entidades',
							  handler: function(){
				                   IdReporteSel='8';
								   WinFnarbolReportes()
				                }
							 },
							 {
						     text:'Relaci&oacute;n de Niveles 1 seg&uacute;n su clasificaci&oacute;n',
							  handler: function(){
				                    IdReporteSel='1';
								   WinFnarbolReportes()
				                }
							 },
							 
							 {
						     text:'Resumen de categor&iacute;a de las entidades por agrupaciones',
							  handler: function(){
				                  IdReporteSel='12';
								  WinFnarbolReportes()
				                }
							 },
							 {
						     text:'Resumen de entidades por agrupaci&oacute;n seg&uacute;n su clasificaci&oacute;n',
							  handler: function(){
				                   IdReporteSel='11';
								   WinFnarbolReportes()
				                }
							 },
							 {
						     text:'Cantidad de Entidades y Unidades por Agrupaci&oacute;n de un Nivel 1',
							  handler: function(){
				                   IdReporteSel='4';
								   WinFnarbolReportes()
				                }
							 },
							 {
						     text:'Relaci&oacute;n de entidades por agrupaci&oacute;n',
							  handler: function(){
				                   IdReporteSel='7';
								   WinFnarbolReportes()
				                }
							 }
							 
							]
						}
                },
				{
                    text: 'De la entidad ',
					//icon: '../../comun/recursos/iconos/organigrama/nuevo.gif',
                    iconCls: 'btn',
					menu:{
					items:[
					      {
						    text:'Plantilla de cargos',
							handler: function(){
									IdReporteSel='5';
									var id_estructura;
									Ext.Ajax.request({
									url : 'obteneridestructura',
									method : 'POST',
									callback : function(options, success, response) 
												{
													responseData = Ext.decode(response.responseText);
													id_estructura = responseData.estructura;
                                                                                                        
													 WinFormato(id_estructura);
												}

									});	
							}		
							
						   },
						   {
						     text:'Resumen de cargo por grupo de complejidad y categor&iacute;a ocupacional ',
							  handler: function(){
									IdReporteSel='2';
									var id_estructura;
									Ext.Ajax.request({
									url : 'obteneridestructura',
									method : 'POST',
									callback : function(options, success, response) 
												{
													responseData = Ext.decode(response.responseText);
													id_estructura = responseData.estructura;
													 WinFormato(id_estructura);
												}

									});	
							}
							 },
							 {
						     text:'Resumen de cargos por &aacute;rea y categor&iacute;a ocupacional ',
							  handler: function(){
									
									IdReporteSel='6';
									var id_estructura;
									Ext.Ajax.request({
									url : 'obteneridestructura',
									method : 'POST',
									callback : function(options, success, response) 
												{
													responseData = Ext.decode(response.responseText);
													id_estructura = responseData.estructura;
                                                                                                        WinFormato(id_estructura);
													
												}

									});	
							}
							 }
							]
						}
                }
				
				
				]
                
            }})]
		
		
		})

       //-----------------------------------------------Mis reportes---------------------------------------------------------------
               //-------Ventana para cargar el formato para los reportes de la entidad
               function WinFormato(id_estructura){
                   
                   // ----------------Store del combo de cargar los formatos
                var   strFormatoReporte = new Ext.data.Store({
                    url:'obtenerformatodocumento',
                    reader:new Ext.data.JsonReader({
		   id: 'strFormatoReporte'
		   },
            [
                {name:'idformat'},
                {name:'format'}
            ]
		)
    });
//----------------Combo de cargar los formatos
		var cmbFormatoReporte = new Ext.form.ComboBox({
                        fieldLabel:'Formato',
                        id:'cmbFormatoReporte',
                        store: strFormatoReporte,
                        displayField : 'format',
                        hiddenName : 'idformat',
                        valueField : 'idformat',
                        typeAhead: true,
                        mode: 'local',
                        triggerAction: 'all',
                        forceSelection : true,
                        editable: false,
                        allowBlank: false,
                        selectOnFocus:true,
                        anchor: '90%',
                        listeners:{select: function(combo, record, index){

                                //WinarbolReportes.buttons[0].setDisabled(false);
                                //strFormatoReporte.load();

                        }}

                    });//strFormatoReporte.load();

            strFormatoReporte.load();

cmbFormatoReporte.setValue('default');

                    if(!Windowf){
var    Windowf = new Ext.Window({
            title: 'Formatos',
            layout:'fit',
            items: [cmbFormatoReporte],
            width: 200,
            modal:true,
            height: 100,
            closeAction: 'destroy',
            plain: true,

                                buttons:
                                [{
                                        //text:perfil.etiquetas.lbMsgAceptar,
                                        text:'Mostrar',
                                        //disabled:true,
                                        icon:perfil.dirImg+'aceptar.png',
                                        iconCls:'btn',
                                        handler:function(){
                                                //alert(arbolReportes.getChecked()[0].text);

                                                CargarReportes(id_estructura, cmbFormatoReporte.getRawValue());



                                        }
                          },
                          {
                                        //text:perfil.etiquetas.lbMsgAceptar,
                                        text:'Cancelar',
                                        //disabled:true,
                                        icon:perfil.dirImg+'cancelar.png',
                                        iconCls:'btn',
                                        handler:function(){
                                                Windowf.destroy();
                                        }
                          }
                          ]
});

                    }
          
Windowf.show();
        }

          

                
         
                     //---------Ventana de los reportes--------------
	   function WinFnarbolReportes()
		{

             //----------------Store del combo de cargar los formatos
                var   strFormatoReporte = new Ext.data.Store({
                    url:'obtenerformatodocumento',
                    reader:new Ext.data.JsonReader({
		   id: 'strFormatoReporte'
		   },
            [
                {name:'idformat'},
                {name:'format'}
            ]
		)
    });
//----------------Combo de cargar los formatos
		var cmbFormatoReporte = new Ext.form.ComboBox({
                        fieldLabel:'Formato',
                        id:'cmbFormatoReporte',
                        store: strFormatoReporte,
                        displayField : 'format',
                        hiddenName : 'idformat',
                        valueField : 'idformat',
                        typeAhead: true,
                        mode: 'local',
                        triggerAction: 'all',
                        forceSelection : true,
                        editable: false,
                        allowBlank: false,
                        selectOnFocus:true,
                        anchor: '100%',
                        listeners:{select: function(combo, record, index){

                                //WinarbolReportes.buttons[0].setDisabled(false);
                                //strFormatoReporte.load();

                        }}

                    });strFormatoReporte.load();

           cmbFormatoReporte.setValue('default');

             
             var loaderarbolReportes=new Ext.tree.TreeLoader({
							dataUrl:'buscarhijosreportes'
							
							
					});
		
		
		var arbolReportes = new Ext.tree.TreePanel({
	        			autoScroll:true,	
					    width: 200,
	        			minSize: 175,
	        			maxSize: 300,
	        			height:300,
	       				margins:'0 0 0 2',
                        disabled:true,
	        			layoutConfig:{animate:true},
						region:'west',						
	        			//enableDD:false,
	        			containerScroll: true, 
	        			loader: loaderarbolReportes,
						listeners:{
							checkchange:function (n,c){
								//alert(arbolReportes.getChecked().length);
                                                                 


								var arr = arbolReportes.getChecked();
								if (arr.length > 1){
									arr[0].ui.toggleCheck(false);
									arr[1].ui.toggleCheck(false);
                                                                        n.ui.toggleCheck(true);
                                                                         
								}
                                                                    
								WinarbolReportes.buttons[0].setDisabled(!c);
                                                                				}
						}
                       /* listeners:{
						      click:function(n)
							  {							   
								var comp=Ext.getCmp('espec');
								var tf_idesp=Ext.getCmp('idtxtespecialidad');
							    comp.setValue(n.text);
								tf_idesp.setValue(n.id);
								idesp=n.id;
								WintreeEspecialidadCM.hide();
							  
							  }
						
						}	*/					
	        			
			});
                        /*  if(IdReporteSel == 1){
                  arbolReportes.setd  ;
              }*/
	    var rootarbolReportes = new Ext.tree.AsyncTreeNode({
       					text: 'Estructuras',
       					iconCls:'btn',
        				draggable:false,
        				id:'Estructuras'
					});
				
		arbolReportes.setRootNode(rootarbolReportes);
		
		
		
		
	         
	   
		   if (!WinarbolReportes) {
		   	
              var  WinarbolReportes = new Ext.Window({
						                    title: 'Reportes mios',
						                    layout:'fit',
						                    items: [arbolReportes],
						                    width: 300,
								    modal:true,
						                    height: 400,
						                    closeAction: 'destroy',
						                    plain: true,
                                                                    
											buttons:											
											[{
												//text:perfil.etiquetas.lbMsgAceptar,
												text:'Mostrar',
												disabled:true,
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												handler:function(){
													//alert(arbolReportes.getChecked()[0].text);
                                                                                                        if(IdReporteSel !=1)
													CargarReportes(arbolReportes.getChecked()[0].text , cmbFormatoReporte.getRawValue());
                                                                                                    else
                                                                                                        CargarReportes(null , cmbFormatoReporte.getRawValue());
													
												}
										  },
										  {
												//text:perfil.etiquetas.lbMsgAceptar,
												text:'Cancelar',
												//disabled:true,
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												handler:function(){
													WinarbolReportes.destroy();
                                                                                                        
												}
										  }
										  ],bbar: ['Formato de reporte: ',cmbFormatoReporte]
						                
						                });
            }

            WinarbolReportes.show();
            if(IdReporteSel == 1){
                    arbolReportes.disable();
                   WinarbolReportes.buttons[0].setDisabled(false);
                }

              else{
                  arbolReportes.enable();

              }
	    arbolReportes.show();

		
		
		}
		 
                 //---------------------Ventana para el calificador
		 function FnWinReporteCalificador()
		{
		 //----------------Store del combo de cargar los formatos
                var   strFormatoReporte = new Ext.data.Store({
                    url:'obtenerformatodocumento',
                    reader:new Ext.data.JsonReader({
		   id: 'strFormatoReporte'
		   },
            [
                {name:'idformat'},
                {name:'format'}
            ]
		)
    });
//----------------Combo de cargar los formatos
		var cmbFormatoReporte = new Ext.form.ComboBox({
                        fieldLabel:'Formato',
                        id:'cmbFormatoReporte',
                        store: strFormatoReporte,
                        displayField : 'format',
                        hiddenName : 'idformat',
                        valueField : 'idformat',
                        typeAhead: true,
                        mode: 'local',
                        triggerAction: 'all',
                        forceSelection : true,
                        editable: false,
                        allowBlank: false,
                        selectOnFocus:true,
                        anchor: '100%',
                        autoCreate: true,
                        listeners:{select: function(combo, record, index){
                               
                                //WinarbolReportes.buttons[0].setDisabled(false);
                                //strFormatoReporte.load();
                               
                        }}

                    });//strFormatoReporte.load();
                 strFormatoReporte.load();

             cmbFormatoReporte.setValue('default');





			var st_calificador = new Ext.data.Store({
			    							autoLoad: false,
			    							url: 'mostrarcalificador',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idcalificador"    
			    										},[{
			        										name: 'idcalificador'				        									
			        									},{
			        						 				name: 'denominacion'}] 
											)
							});
			st_calificador.load();
			var cb_calificador = new Ext.form.ComboBox({
								//xtype:'combo',
								fieldLabel: 'Calificador',
								store:st_calificador,
								editable :false,
								triggerAction:'all',
								allowBlank:false,
								forceSelection:true,
								emptyText:'Seleccione el tipo..',
								hideLabel:false,
								autoCreate: true,
								mode: 'local',
								forceSelection: true,
								anchor:'100%',
								//id:"especialidad",
								valueField:'idcalificador',
								hiddenName:'idcalificador',
								displayField:'denominacion',
								//listeners://{select:OnEscalaSalarialSelect}
								listeners:{select:function (  combo, record, index ){
													//alert(arbolReportes.getChecked().length);
													//var denominacion = combo.getRawValue();
													//alert(denominacion);
														WinReporteCalificador.buttons[0].enable();
                                                                                                              
                                                                                                                    }
													//WinarbolReportes.buttons[0].setDisabled(!c);
												}
											
								});	
			
		 var  fm_Calificador = new Ext.FormPanel({
								 labelAlign: 'top',
								 frame:true,
								 autoHeight:true,
								 border:'false',
                                                                 items:[cb_calificador,cmbFormatoReporte]//[{layout:'column',items: cb_calificador }]
								 
						});
		   if (!WinReporteCalificador) {
		   	
           var  WinReporteCalificador = new Ext.Window({
						                    title: 'Calificadores',
						                    layout:'fit',
						                    items: fm_Calificador,                                        
						                    width: 300,
                                                                    modal:true,
						                    height: 180,
						                    closeAction: 'destroy',
						                   // plain: true,
											buttons:											
											[{
												//text:perfil.etiquetas.lbMsgAceptar,
												text:'Mostrar',
												disabled:true,
												icon:perfil.dirImg+'aceptar.png',
												iconCls:'btn',
												handler:function(){
																
													//alert(cb_calificador.getRawValue());
													//alert(arbolReportes.getChecked()[0].text);
													CargarReportes(cb_calificador.getRawValue(),cmbFormatoReporte.getRawValue());
                                                                                                        //fm_Calificador.getForm().reset();
													//WinReporteCalificador.destroy();
												}
										  },
										  {
												//text:perfil.etiquetas.lbMsgAceptar,
												text:'Cancelar',
												//disabled:true,
												icon:perfil.dirImg+'cancelar.png',
												iconCls:'btn',
												handler:function(){
													WinReporteCalificador.destroy();
												}
										  }
										  ]
						                
						                });
            }
            WinReporteCalificador.show();
	
		
		}
        
 
				
                   
function CargarReportes(estructura,format){

   var valores= [];
   valores.push(estructura);
   var param = Ext.encode(valores);
   var pos=0;
for(var i=0;i<array_reporte[0].reportes.length;i++){
if(IdReporteSel==1 && array_reporte[0].reportes[i].denominacion=='EyC-Relaci\u00f3n de Niveles 1 seg\u00fan su clasificaci\u00f3n')
pos=i;

if(IdReporteSel==2 && array_reporte[0].reportes[i].denominacion=='EyC-Resumen de cargo por grupos de complejidad y categor\u00eda ocupacional')
pos=i;

if(IdReporteSel==3 && array_reporte[0].reportes[i].denominacion=='EyC-Calificador de cargos t\u00e9cnicos comunes')
pos=i;

if(IdReporteSel==4 && array_reporte[0].reportes[i].denominacion=='EyC-Cantidad de Entidades y Unidades por Agrupaci\u00f3n de un Nivel 1')
pos=i;

if(IdReporteSel==5 && array_reporte[0].reportes[i].denominacion=='EyC-Plantilla de cargos')
pos=i;

if(IdReporteSel==6 && array_reporte[0].reportes[i].denominacion=='EyC-Resumen de cargos por \u00e1reas y categor\u00eda ocupacional')
pos=i;

if(IdReporteSel==7 && array_reporte[0].reportes[i].denominacion=='EyC-Relaci\u00f3n de Entidades por Agrupaci\u00f3n')
pos=i;

if(IdReporteSel==8 && array_reporte[0].reportes[i].denominacion=='EyC-Relaci\u00f3n de localizaci\u00f3n de las unidades por Entidades')
pos=i;

if(IdReporteSel==9 && array_reporte[0].reportes[i].denominacion=='EyC-Relaci\u00f3n de registros de las entidades por agrupaci\u00f3n')
pos=i;

if(IdReporteSel==10 && array_reporte[0].reportes[i].denominacion=='EyC-Relaci\u00f3n de la localizaci\u00f3n de las entidades por nivel estructural')
pos=i;

if(IdReporteSel==11 && array_reporte[0].reportes[i].denominacion=='EyC-Resumen de entidades por agrupaci\u00f3n seg\u00fan su clasificaci\u00f3n')
pos=i;

if(IdReporteSel==12 && array_reporte[0].reportes[i].denominacion=='EyC-Resumen de categor\u00eda de las entidades por agrupaciones')
pos=i;

}
switch(IdReporteSel){
                case '1':
                        v_urlreporte = array_reporte[0].reportes[pos].url+'&format='+format;
                        break;
                case '2':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                         break;
                case '3':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                          break;

                case '4':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                        break;
                case '5':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                         break;
                case '6':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                         break;

                case '7':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                         break;
                case '8':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                         break;
                case '9':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                          break;
                case '10':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                         break;
                case '11':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                         break;
                case '12':

                         v_urlreporte = array_reporte[0].reportes[pos].url+'&pvalues='+param+'&format='+format;
                         break;
        }

var wCfg = 'titlebar=yes,status=yes,resizable=yes,width='+screen.width+',height='+screen.height+',scrollbars='+1;
var ventana = window.open(v_urlreporte,'ventana1',wCfg);

}
	   
	   
	   
	   
	   //-----------------------------------------------Organigrama----------------------------------------------------------------
	   tree = new Ext.tree.TreePanel({
            //useArrows: true,
            autoScroll: true,
            margins:'0 0 0 2',
            width: 200,
            minSize: 175,
            maxSize: 400,
            height: 600,
            collapsible: false,
            title: 'Estructuras',
            layoutConfig:{animate:true},
            enableDD: false,
            region: 'center',
            containerScroll: true,
            dataUrl: 'buscarhijos',
            root: {
                nodeType: 'async',
                icono: perfil.dirImg+'/organigrama/forum.gif',//'../../comun/recursos/iconos/organigrama/menu/forum.gif',
                text: 'ministerios',
                draggable: false,
                id: "Estructuras"
            }
        })
        
        tree.getRootNode().expand();
        
        
        tree.on("click", function(n){
            nodosel = n;            
        })
        
		function Chekear(n,dat){		
			var nodeui=this.getUI();		
			var cb = nodeui.checkbox;
	        if(cb){
	            cb.checked = (dat === undefined ? !cb.checked : dat);				
				var checked = cb.checked;
		        nodeui.checkbox.defaultChecked = checked;
		        nodeui.node.attributes.checked = checked;	
			}		
		}
        
		tree.on("checkchange",function(n,check){
				  var arr=[];
		          arr[0]=n;		  
				  arr[1]=check;		  
				  n.cascade(Chekear,'',arr)		  
			})	
      
	  var Tree = Ext.tree;
        
        var loader_comp = new Tree.TreeLoader({
            dataUrl: 'buscarcomposicion'
        });
               
        
        
        
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
                tamNodo = longuitud * 20;
            else 
                if (longuitud <= 3) 
                    tamNodo = longuitud * 45;
                
                else 
                    tamNodo = longuitud * 15;
					
		return 	tamNodo;		
        }
        
       
	  
	   
        function creaArray(idEstructura){
        
            arrNodo=[];
			var datos = [];
            
            if (idEstructura.length >= 1) 
                datos = idEstructura
           
            
            nodes = datos;
            
            if (idEstructura.length >= 1) {
            
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
				var aux2='';
				var tipPintar=null;
				var pint=null;
                
                for (i = 0; i < cant; i++) {
                    aux = ''
                    aux =  tree.getNodeById(datos[i]);
					
					tipPintar=aux.attributes.pintar;
					pint=aux.attributes.pintado;
					
					if(i<cant-1) 
					aux2=  tree.getNodeById(datos[i+1]);
					
					
                    var idPadre = null;
                    if (aux.attributes.id == aux.attributes.idpadre) {
                        idPadre = -1;
                        
                    }
                    else 
                        idPadre = aux.attributes.idpadre; 
                        var ind=i+1;
                if(i==cant-1 && !aux.attributes.pintar)	
                       {
					     tipPintar=true;	
                         pint=true;
					   }
                     else					   
                if(i<cant-1 &&  aux.attributes.id!= aux2.attributes.idpadre && !aux.attributes.pintar)
                 	{
							tipPintar=true;	
                            pint=true;							
				            }
							
							
                    arrNodo[i] = {
                        id: aux.attributes.id,
                        idpadre: idPadre,
                        texto: aux.attributes.text,
                        icono: perfil.dirImg+'/organigrama/'+aux.attributes.icon+'.gif',//aux.attributes.icon,//'./comun/recursos/iconos/'+ico+'.gif',//''../../comun/recursos/iconos/organigrama/'+aux.attributes.icon,
                        indice: ind,
						profundidad: aux.getDepth(),
						nota:"",
						tipoPintar:tipPintar,//aux.attributes.pintar,
						pintado:pint,
						agrupar:aux.attributes.agrupar,
						cantidadAgrupar:aux.attributes.cantidadAgrupar
                    };
                    
                }
                
                
                PintarOrganigrama(arrNodo);
                
                
              //  t.UpdateTree();
                
                
            }
            else 
			mostrarMensaje(1, "Debe seleccionar al menos una estructura")
              
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
                    
                        t.add(arrNodos[j].id, arrNodos[j].idpadre, arrNodos[j].texto, arrNodos[j].icono,arrNodos[j].indice,arrNodos[j].nota,arrNodos[j].tipoPintar,arrNodos[j].pintado,arrNodos[j].agrupar,arrNodos[j].cantidadAgrupar, tamNodo);
                        cont++;
                        
                    }
                    else {
                    
                        t.add(arrNodos[j].id, -1, arrNodos[j].texto, arrNodos[j].icono,arrNodos[j].indice,arrNodos[j].nota,arrNodos[j].tipoPintar,arrNodos[j].pintado,arrNodos[j].agrupar,arrNodos[j].cantidadAgrupar, tamNodo);
                        
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
            icon: perfil.dirImg+'/organigrama/list-items.gif',//'../../comun/recursos/iconos/organigrama/menu/list-items.gif',
            iconCls: 'btn',
            // Menus can be built/referenced by using nested menu config objects
            menu: {
                items: [{
                    text: 'Nuevo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+N)',
					icon: perfil.dirImg+'/organigrama/nuevo.gif',//'../../comun/recursos/iconos/organigrama/nuevo.gif',
                    iconCls: 'btn'
					// handler: onItemClick
                },
                {
                    text: 'Abrir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+A)',
					icon: perfil.dirImg+'/organigrama/recuperar.gif',//'../../comun/recursos/iconos/organigrama/recuperar.gif',
                    iconCls: 'btn'
					//, handler: onItemClick},
                },
				{
                    text: 'Guardar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+G)',
					icon: perfil.dirImg+'/organigrama/salvar.gif',//'../../comun/recursos/iconos/organigrama/salvar.gif',
                    iconCls: 'btn'
					//, handler: onItemClick},
                },
				{
                    text: 'Resumen del personal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+P)',
					icon: perfil.dirImg+'/organigrama/cat_users_16.png',//'../../comun/recursos/iconos/organigrama/cat_users_16.png',
                    iconCls: 'btn',
					handler: resumenPersonal
                },
				{
                    text: 'Resumen medios t&eacute;cnicos &nbsp;&nbsp; (Alt+M)',
					icon: perfil.dirImg+'/organigrama/custom_16.png',//'../../comun/recursos/iconos/organigrama/custom_16.png',
                    iconCls: 'btn',
					handler: resumenMediosTecnicos
                },
				{
                    text: 'Agregar notas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+S)',
					icon: perfil.dirImg+'/organigrama/notas.gif',//'../../comun/recursos/iconos/organigrama/notas.gif',
                    iconCls: 'btn',
					handler: selectedNodes
                },
				{
                    text: 'Collapsar todos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+C)',
					icon: perfil.dirImg+'/organigrama/plus.gif',//'../../comun/recursos/iconos/organigrama/plus.gif',
                    iconCls: 'btn',
					handler: function(){t.collapseAll();}
                },
				{
                    text: 'Expandir todos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Alt+E)',
					icon: perfil.dirImg+'/organigrama/less.gif',//'../../comun/recursos/iconos/organigrama/less.gif',
                    iconCls: 'btn',
					handler: function(){t.expandAll();}
                },
				{
                    text: 'Buscar nodo (Alt+I)',
					icon: perfil.dirImg+'/organigrama/buscar.gif',//'../../comun/recursos/iconos/organigrama/buscar.gif',
                    iconCls: 'btn',
					handler: buscarNodo
                },
                {
                    text: 'Imprimir (Alt+I)',
					icon: perfil.dirImg+'/organigrama/imprimir.gif',//'../../comun/recursos/iconos/organigrama/imprimir.gif',
                    iconCls: 'btn'
					//, handler: onItemClick
                },
                {
                    text: 'Cerrar Ventana&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Alt+R)',
					icon: perfil.dirImg+'/organigrama/delete_16.png',//'../../comun/recursos/iconos/organigrama/delete_16.png',
                    iconCls: 'btn',
					handler: function(){win.hide()}
                }]
            }
        })
        
        
        var botonVer = new Ext.Toolbar.Button({
            text: 'Ver',
            icon: perfil.dirImg+'/organigrama/menu-show.gif',//'../../comun/recursos/iconos/organigrama/menu/menu-show.gif',
            iconCls: 'btn',
            // Menus can be built/referenced by using nested menu config objects
            menu: {
                items: [{
                    text: 'Relleno',
                    menu: {
                        items: [{
                            text: 'S&oacute;lido',
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
                    text: 'Posici&oacute;n',
                    
                    menu: { // <-- submenu by nested config object
                        items: [ // stick any markup in a menu
                        {
                            text: 'Arriba->Abajo',
                            checked: true,
                            group: 'posicion',
                            checkHandler: function(){
                                ChangePosition(0)
                            }
                        }/*, {
                            text: 'Abajo->Arriba',
                            checked: false,
                            group: 'posicion',
                            checkHandler: function(){
                                ChangePosition(1)
                            }
                        }, {
                            text: 'Derecha->Izquierda',
                            checked: false,
                            group: 'posicion',
                            checkHandler: function(){
                                ChangePosition(2)
                            }
                        }, {
                            text: 'Izquierda->Derecha',
                            checked: false,
                            group: 'posicion',
                            checkHandler: function(){
                                ChangePosition(3)
                            }
                        }*/]
                    }
                }, {
                    text: 'L&iacute;neas',
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
                    text: 'Estilo de colores',
                    menu: {
                        items: [{
                            text: 'Color niveles',
                            checked: false,
                            group: 'estilo',
                            checkHandler: function(){
                                ChangeColorStyle(1)
                            }
                        }, {
                            text: 'Color nodo',
                            checked: true,
                            group: 'estilo',
                            checkHandler: function(){
                                ChangeColorStyle(0)
                            }
                        }]
                    }
                }, {
                    text: 'Alineaci&oacute;n',
                    menu: { // <-- submenu by nested config object
                        items: [ // stick any markup in a menu
                        {
                            text: 'Arriba',
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
					icon: perfil.dirImg+'/organigrama/color.gif',//'../../comun/recursos/iconos/organigrama/color.gif',
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
                    text: 'Buscar por t&iacute;tulo',
					icon: perfil.dirImg+'/organigrama/buscar.gif',//'../../comun/recursos/iconos/organigrama/buscar.gif',
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
					icon: perfil.dirImg+'/organigrama/polls_16.png',//'../../comun/recursos/iconos/organigrama/polls_16.png',
                    iconCls: 'btn',
					handler: function(){ventanaAyuda('<br><img src="img/ej orga.gif" width="292" height="197" align="top"><br><br><div align="justify">This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details</div>')}
                }]
            }
        })
        
        var botonp = new Ext.Toolbar.Button({
            icon: perfil.dirImg+'/organigrama/Ar-Ab.gif',//'../../comun/recursos/iconos/organigrama/Ar-Ab.gif',
            iconCls: 'btn',
            tooltip: 'Ver organigrama de arriba a abajo',
            handler: function(){
                ChangePosition(0)
            }
        })
        
		 textNotas=new Ext.form.TextArea({
		    hideLabel: true,
			grow : true,
			height:250,
			preventScrollbars:true,
            name: 'msg',
            anchor: '100%'  // anchor width by percentage and height by raw adjustment
		})
		
		/*var PanelNotas=new Ext.Panel({
		title:'Agregar Notas',		
		layout:'fit',
		collapsible: true,
		split:true,
        height: 100,		
		autoWidth:true,
        minSize: 100,
        maxSize: 200,
		margins:'0 0 0 0',
		iconCls:'detalles',
		autoWidth:true,
		autoScroll:true,
		items:[textNotas]
	})*/
        
         panelOrganigrama = new Ext.Panel({
            contentEl: 'hello-win',
            width: 680,	
			title:' ',
            height: 370,
			listeners:{
			  titlechange:function(p,text)
			  {
			    tituloOrganigrama=text;
				
			  }
			}
        })
        
		
        
         paneltbar = new Ext.Panel({
            width: 680,
            height: 370,			
            frame: false,
            items: [panelOrganigrama]
        
        })
        var panelMenu = new Ext.Panel({
		    layout:'fit',
            tbar: [botonArchivo, botonVer, botonBuscar, botonAyuda],
            //autoWidth:true,
            bodyBorder: false,
            border: false,
			//autoWidth:true,
            frame: false,
            region: 'north'
        
        })
        var panelTolbar = new Ext.Panel({
		    layout:'fit',
            border: false,
			//autoWidth:true,
			hideBorders:true,
			draggable: {
//      Config option of Ext.Panel.DD class.
//      It's a floating Panel, so do not show a placeholder proxy in the original position.
        insertProxy: false,

//      Called for each mousemove event while dragging the DD object.
        onDrag : function(e){
//          Record the x,y position of the drag proxy so that we can
//          position the Panel at end of drag.
            var pel = this.proxy.getEl();
            this.x = pel.getLeft(true);
            this.y = pel.getTop(true);

//          Keep the Shadow aligned if there is one.
            var s = this.panel.getEl().shadow;
            if (s) {
                s.realign(this.x, this.y, pel.getWidth(), pel.getHeight());
            }
        },

//      Called on the mouseup event.
        endDrag : function(e){
            this.panel.setPosition(this.x, this.y);
        }
    },
            bodyBorder: false,
            frame: false,
            tbar: [botonp/*,new Ext.Toolbar.Button({
                icon: '../../comun/recursos/iconos/organigrama/Ab-Ar.gif',
                iconCls: 'btn',
	     disabled:true, 
                tooltip: 'Ver organigrama de abajo a arriba',
                handler: function(){
                    ChangePosition(1)
                }
            }), new Ext.Toolbar.Button({
                icon: '../../comun/recursos/iconos/organigrama/D-I.gif',
                iconCls: 'btn',
	     disabled:true, 
                tooltip: 'Ver organigrama de derecha a izquierda',
                handler: function(){
                    ChangePosition(2)
                }
            }), new Ext.Toolbar.Button({
                icon: '../../comun/recursos/iconos/organigrama/I-D.gif',
                iconCls: 'btn',
	     disabled:true, 			
                tooltip: 'Ver organigrama de izquierda a derecha',
                handler: function(){
                    ChangePosition(3)
                }
            })*/,"-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/solido.gif',//'../../comun/recursos/iconos/organigrama/solido.gif',
                iconCls: 'btn',
                tooltip: 'Ver nodos s&oacute;lidos',
                handler: function(){
                    ChangeNodeFill(1)
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/degradao.gif',//'../../comun/recursos/iconos/organigrama/degradao.gif',
                iconCls: 'btn',
                tooltip: 'Ver nodos con degradado',
                handler: function(){
                    ChangeNodeFill(0)
                }
            }), "-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/recta.gif',//'../../comun/recursos/iconos/organigrama/recta.gif',
                iconCls: 'btn',
                tooltip: 'Ver l&iacute;nea recta',
                handler: function(){
                    ChangeLinkType('M')
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/curva.gif',//'../../comun/recursos/iconos/organigrama/curva.gif',
                iconCls: 'btn',
                tooltip: 'Ver l&iacute;nea s&oacute;lida',
                handler: function(){
                    ChangeLinkType('B')
                }
            }), "-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/clevel.gif',//'../../comun/recursos/iconos/organigrama/clevel.gif',
                iconCls: 'btn',
                tooltip: 'Ver color por niveles',
                handler: function(){
                    ChangeColorStyle(1)
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/cnodo.gif',//'../../comun/recursos/iconos/organigrama/cnodo.gif',
                iconCls: 'btn',
                tooltip: 'Ver color por nodos',
                handler: function(){
                    ChangeColorStyle(0)
                }
            }),"-", new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/plus.gif',//'../../comun/recursos/iconos/organigrama/plus.gif',
                iconCls: 'btn',
                tooltip: 'Colapsar todos',
                handler: function(){
                    t.collapseAll();
                }
            }), new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/less.gif',//'../../comun/recursos/iconos/organigrama/less.gif',
                iconCls: 'btn',
                tooltip: 'Expandir todos',
                handler: function(){
                   t.expandAll();
                }
            }), "-",new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/color.gif',//'../../comun/recursos/iconos/organigrama/color.gif',
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
                icon: perfil.dirImg+'/organigrama/salvar.gif',//'../../comun/recursos/iconos/organigrama/salvar.gif',
                iconCls: 'btn',
				id:'btnSalvarOrganigrama',
                tooltip: 'Salvar organigrama',
                handler: function(){                   
				   salvarOrganigrama();
                }
            })/*, new Ext.Toolbar.Button({
                icon: '../../comun/recursos/iconos/organigrama/imprimir.gif',
                iconCls: 'btn',
                tooltip: 'Imprimir organigrama',
                handler: function(){
                    
                }
            })*/, new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/recuperar.gif',//'../../comun/recursos/iconos/organigrama/recuperar.gif',
                iconCls: 'btn',
				id:'btnRecuperarOrganigrama',
                tooltip: 'Recuperar organigrama',
                handler: function(){
                   VentanaGridOrganigramas()
                }
            }),"-",new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/cat_users_16.png',//'../../comun/recursos/iconos/organigrama/cat_users_16.png',
                iconCls: 'btn',
				id:'btnResumenPersonal',
                tooltip: 'Resumen del personal',
                handler: function(){
				
				resumenPersonal();
                   
                }
            }),new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/custom_16.png',//'../../comun/recursos/iconos/organigrama/custom_16.png',
                iconCls: 'btn',
				id:'btnResumenMediosTecnicos',
                tooltip: 'Resumen de medios t&eacute;cnicos',
                handler: function(){
				resumenMediosTecnicos();
                   
                }
            }),"-",new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/notas.png',//'../../comun/recursos/iconos/organigrama/notas.gif',
                iconCls: 'btn',
				id:'btnNotas',
                tooltip: 'Adicionar notas al nodo seleccionado',
                handler: function(){
				selectedNodes();
                   
                }
            }),new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/building_edit.png',//'../../comun/recursos/iconos/organigrama/building_edit.png',
                iconCls: 'btn',
				id:'btnaNotas',
                tooltip: 'Adicionar notas ',
                handler: function(){
				adicionarNotas();
                   
                }
            }),"-",new Ext.Toolbar.Button({
                icon: perfil.dirImg+'/organigrama/buscar.gif',//'../../comun/recursos/iconos/organigrama/buscar.gif',
                iconCls: 'btn',
				id:'btnBuscar',
                tooltip: 'Buscar nodo',
                handler: function(){
				buscarNodo();
                   
                }
            })],
            width: 750,
            region: 'center'
        
        })
		var storeNivelVisibiliad= new Ext.data.Store({
			    							autoLoad: true,
			    							url: 'mostarnivel',
			    							reader: new Ext.data.JsonReader({
			       										totalProperty: "cant",
			        									root: "datos",
			        									id: "idnivel"    
			    										},[{
			        										name: 'inivel'},{
			        						 				name: 'nivel'}] 
											)
							}
    						 );
		
		
		formularioDatOrganigrama=new Ext.FormPanel({		
		frame:true,        
        bodyStyle:'padding:5px 5px 0',
		labelWidth:120,
		labelAlign :'top',
        autoWidth: true,
        defaults: {width: 170},
        defaultType: 'textfield',

        items: [{
                fieldLabel: 'Nombre',
                name: 'nombre',
				id:'nombreOrganigrama',
                allowBlank:false
            }/*,{
                fieldLabel: 'Confeccionado por',
                name: 'nombreconfecciona',
                allowBlank:false
            },{
				xtype:'combo', 
				fieldLabel: 'Nivel visivilidad',
				id:"nivelvisivilidad",
				editable :false,
				//allowBlank:false,				
				triggerAction:'all',
				forceSelection:true,
				emptyText:'Seleccione nivel..',					
				hideLabel:false,
				autoCreate: true,
				mode: 'local',
				forceSelection: true,				
				store:storeNivelVisibiliad,
				displayField:'dnivel',
				valueField:'idnivel',
				hiddenName:'idnivel'
						}*/
        ]
		})
		
		function adicionarNotas()
		{
		     if(!winaddNotas){
            winaddNotas = new Ext.Window({                
                layout      : 'fit',
                width       : 350,
                height      : 300,
                closeAction :'hide',
				title:'Notas',
                plain       : true,
				modal:true,
               items:[textNotas],
                buttons: [{
                    text     : 'Cerrar',
                    handler  : function(){
                        winaddNotas.hide();
                    }
                }]
            });
        }
        winaddNotas.show();
		  
		}
		
		function winEntrarNombreOrganigrama()
		{
		  if(tree.getChecked('id').length>=1)
		  {
		  
		   if(!winDatosOrganigrama){
            winDatosOrganigrama = new Ext.Window({                
                layout      : 'fit',
                width       : 250,
                height      : 300,
                closeAction :'hide',
				title:'Datos para generar el organigrama',
                plain       : true,
				modal:true,
                items:[formularioDatOrganigrama],
                buttons: [{
                    text     : 'Generar organigrama',
					handler:function(){
					if(formularioDatOrganigrama.getForm().isValid())
					{
					crearOrganigrama();
					winDatosOrganigrama.hide();
					formularioDatOrganigrama.getForm().reset()
					}
					else
					mostrarMensaje(3, "Por favor verifique nuevamente que hay campo(s) con valor(es) incorrecto(s).");
					}
                },{
                    text     : 'Cerrar',
                    handler  : function(){
                        winDatosOrganigrama.hide();
                    }
                }]
            });
        }
        winDatosOrganigrama.show();
    }
		 // Ext.MessageBox.prompt('Crear Organigrama', 'Entre el nombre con el que desea generar el organigrama:', crearOrganigrama);
		
		else
		mostrarMensaje(1, "Debe seleccionar al menos una estructura.")
		}
		
		function crearOrganigrama()
		{ 
		    
			 creaArray(tree.getChecked('id'));
			 var tit=formularioDatOrganigrama.findById('nombreOrganigrama').getValue();
			 formularioDatOrganigrama.getForm().reset();
			 panelOrganigrama.setTitle(tit);
			 winDatosOrganigrama.hide();
		  
		}
		function salvarOrganigrama()
		{
		   Ext.MessageBox.prompt('Salvar Organigrama', 'Entre el nombre con el que desea guardar el organigrama:', enviarOrganigrama,'','',tituloOrganigrama);
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
           success: function(){ },          		   
           params: {nombre: text,nodos: Ext.encode(arrNodo) ,notas:textNotas.getValue()}
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
        header: "otro",
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
                    title: 'Resumen del personal',
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
                    title: 'Resumen de medios t&eacute;cnicos',
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
		     autoWidth:true,	
             items:[panelMenu,panelTolbar]			 
           /* tbar:[panelMenu],
			bbar:[panelTolbar]*/
        })
        
        var panelC = new Ext.Panel({
		     region:'center',
		     //autoWidth:true,	
             //items:[panelMenu,panelTolbar]			 
           /* tbar:[panelMenu],
			bbar:[panelTolbar]*/
        })
        var viewport = new Ext.Viewport({
            layout: 'border',
            items: [panelSuperior,panelC/*,tree*/]
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
                columns: [{header: "Nombre Organigrama",sortable: true,dataIndex: 'Nombre'}],
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


    
	    gridOrganigramas.on('rowcontextmenu',function(grid,rowIndex,e){MenuGridOrganigrama.showAt(e.getXY());})
	 
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
           msg: 'Por favor entre el nombre del organigrama.',
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
           		   
           params: {nombre: modoSeleccionOrganigramas.getSelected().get('Nombre'), nuevoNombre:text}
         });
			   
	    }
	              }
	} 
		
		function EliminarOrganigrama()
		{
		
		 if(modoSeleccionOrganigramas.getSelections().length>=1)
		  {
		   
		    var msg='&iquest;Est&aacute; seguro que desea eliminar '+modoSeleccionOrganigramas.getSelected().get('Nombre')+'?'
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
           params: {nombre: modoSeleccionOrganigramas.getSelected().get('Nombre')}
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
			 if(res.length>0)
			 {
			 var not=res.notas[0];			 			 
			 PintarOrganigrama(res.nodes);
			 textNotas.setValue(not.texto);
			 panelOrganigrama.setTitle(modoSeleccionOrganigramas.getSelected().get('Nombre'));
			 }
      	   
		   
		   },
           //failure: otherFn,  
           params: {nombre: modoSeleccionOrganigramas.getSelected().get('Nombre')}
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
			
			modoSeleccionOrganigramas.clearSelections();
			btnEliminarOrganigrama.disable();
			btnModificarOrganigrama.disable();
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
					maximizable:true,					
                    plain: true
                
                });
            }
            win.show();
              
            
        }
		
		function ventanaAyuda(html)
		{
		     if (!winAyuda) {
                winAyuda = new Ext.Window({
                    title: 'Acerca de Organigrama',                    
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
           msg: 'Entre el nombre del nodo a buscar.',
           buttons: Ext.MessageBox.OK,
           //animEl: 'btnNotas',
           fn: buscarNodo,
           icon: Ext.MessageBox.ERROR
       });
	}
	else
  if(! t.searchNodes(text) && btn!="cancel")
  {
   
     Ext.MessageBox.show({
           title: 'Error',
           msg: 'No existe ningun nodo llamado '+text+'.',
           buttons: Ext.MessageBox.OK,
           //animEl: 'btnNotas',
          // fn: buscarNodo,
           icon: Ext.MessageBox.ERROR
       });
   }
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
           msg: 'Por favor seleccione el nodo al que le desea agregar nota.',
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
           msg: 'Por favor entre la nota aqui:',
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
  //console.info(arrNodo)
  
 var valor=textNotas.getValue()
 var inicio=" ";
 if(valor && valor!="")
 inicio=" .";
 if(valor)
 textNotas.setValue(valor+inicio+"En "+arrNodo[ind].texto+"("+arrNodo[ind].indice+") "+text)
 else
 if(!valor)
textNotas.setValue(inicio+"En "+arrNodo[ind].texto+"("+arrNodo[ind].indice+") "+text);
 
  
  
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
